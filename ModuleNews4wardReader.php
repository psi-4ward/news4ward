<?php if(!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * News4ward
 * a contentelement driven news/blog-system
 *
 * @author Christoph Wiechert <wio@psitrax.de>
 * @copyright 4ward.media GbR <http://www.4wardmedia.de>
 * @package news4ward
 * @filesource
 * @licence LGPL
 */
 
class ModuleNews4wardReader extends News4ward
{
    /**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_reader';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### News4ward READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news4ward_archives));

		// Return if there are no archives
		if (!is_array($this->news_archives) || count($this->news_archives) < 1)
		{
			return '';
		}

		// Read the alias from the url
		if(!preg_match("~.*/".preg_quote($GLOBALS['objPage']->alias)."/([a-z0-9_-]+).*~i",$this->Environment->request,$erg))
		{
			return '';
		}
		$this->alias = $erg[1];

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
    {
		$time = time();

		/* build where */
		$where = array();

		// news archives
		$where[] = 'tl_news4ward_article.pid IN('. implode(',', array_map('intval', $this->news_archives)) . ')';

		// published
		if(!BE_USER_LOGGED_IN)
		{
			$where[] = "(tl_news4ward_article.start='' OR tl_news4ward_article.start<".$time.") AND (tl_news4ward_article.stop='' OR tl_news4ward_article.stop>".$time.") AND tl_news4ward_article.status='published'";
		}

		// alias
		$where[] = 'tl_news4ward_article.alias = "'.($this->alias).'"';

		// @todo filter protected


		/* get the item */
		$objArticle = $this->Database->prepare("
			SELECT *, author AS authorId,
				(SELECT title FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS archive,
				(SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS parentJumpTo,
				(SELECT name FROM tl_user WHERE id=author) AS author
			FROM tl_news4ward_article
			WHERE ".implode(' AND ',$where))->execute();


		$arrMeta = $this->getMetaFields($objArticle);

		// Add meta information
		$this->Template->date = $arrMeta['date'];
		$this->Template->hasMetaFields = count($arrMeta) ? true : false;
		$this->Template->numberOfComments = $arrMeta['ccount'];
		$this->Template->commentCount = $arrMeta['comments'];
		$this->Template->timestamp = $objArticles->date;
		$this->Template->author = $arrMeta['author'];
		$this->Template->datetime = date('Y-m-d\TH:i:sP', $objArticles->date);


		/* generate the content-elements */
		$objContentelements = $this->Database->prepare('SELECT id FROM tl_content WHERE pid=? AND do="news4ward" ' . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . ' ORDER BY sorting ')->execute($objArticle->id);
		$strContent = '';
		while($objContentelements->next())
		{
			$strContent .= $this->getContentElement($objContentelements->id);
		}

		$this->Template->content = $strContent;

    }


    /**
   	 * Sort out protected archives
   	 * @param array $arrArchives
   	 * @return array
   	 */
   	protected function sortOutProtected($arrArchives)
   	{
   		if (BE_USER_LOGGED_IN || !is_array($arrArchives) || count($arrArchives) < 1)
   		{
   			return $arrArchives;
   		}

   		$this->import('FrontendUser', 'User');
   		$objArchive = $this->Database->execute("SELECT id, protected, groups FROM tl_news4ward WHERE id IN(" . implode(',', array_map('intval', $arrArchives)) . ")");
   		$arrArchives = array();

   		while ($objArchive->next())
   		{
   			if ($objArchive->protected)
   			{
   				if (!FE_USER_LOGGED_IN)
   				{
   					continue;
   				}

   				$groups = deserialize($objArchive->groups);

   				if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
   				{
   					continue;
   				}
   			}

   			$arrArchives[] = $objArchive->id;
   		}

   		return $arrArchives;
   	}
}

?>