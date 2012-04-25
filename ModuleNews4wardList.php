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
 
class ModuleNews4wardList extends News4ward
{
    /**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_list';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### News4ward LIST ###';
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

		// show only highlighted items?
		if($this->news4ward_featured == 'featured')
			$where[] = 'tl_news4ward_article.highlight="1"';
		elseif($this->news4ward_featured == 'unfeatured')
			$where[] = 'tl_news4ward_article.highlight<>"1"';


		// HOOK: add filter logic from other modules like tags
		if(isset($GLOBALS['TL_HOOKS']['News4wardListFilter']) && is_array($GLOBALS['TL_HOOKS']['News4wardListFilter']))
		{
			foreach ($GLOBALS['TL_HOOKS']['News4wardListFilter'] as $callback)
			{
				$this->import($callback[0]);
				$tmp = $this->$callback[0]->$callback[1]($this);

				if (is_string($tmp) && !empty($tmp))
					$where[] = $tmp;
			}
		}


		/* Ordering */
		$ordering = array('tl_news4ward_article.sticky DESC');

		switch($this->news4ward_order)
		{
			case 'title ASC':	$ordering[] = 'tl_news4ward_article.title'; 			break;
			case 'title DESC':	$ordering[] = 'tl_news4ward_article.title DESC'; 		break;
			case 'start ASC':	$ordering[] = 'tl_news4ward_article.start'; 			break;
			case 'start DESC':	$ordering[] = 'tl_news4ward_article.start DESC'; 		break;
		}

		/* Pagination */
		$skipFirst = intval($this->skipFirst);
		$offset = 0;
		$limit = null;

		// Maximum number of items
		if ($this->news4ward_numberOfItems > 0)	$limit = $this->news4ward_numberOfItems;

		// Get the total number of items
		$objTotal = $this->Database->execute("SELECT COUNT(*) AS total FROM tl_news4ward_article WHERE ".implode(' AND ',$where));
		$total = $objTotal->total - $skipFirst;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->news_numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;

			// Check the maximum page number
			if ($page > ($total/$this->perPage))
			{
				$page = ceil($total/$this->perPage);
			}

			// Limit and offset
			$limit = $this->perPage;
			$offset = (max($page, 1) - 1) * $this->perPage;

			// Overall limit
			if ($offset + $limit > $total)
			{
				$limit = $total - $offset;
			}

			// Add the pagination menu
			$objPagination = new Pagination($total, $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}


		/* get the items */
		$objArticlesStmt = $this->Database->prepare("
			SELECT *, author AS authorId,
				(SELECT title FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS archive,
				(SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS parentJumpTo,
				(SELECT name FROM tl_user WHERE id=author) AS author
			FROM tl_news4ward_article
			WHERE ".implode(' AND ',$where)
			.((count($ordering)) ? ' ORDER BY '.implode(',',$ordering) : ''));

		// Limit the result
		if (isset($limit))
		{
			$objArticlesStmt->limit($limit, $offset + $skipFirst);
		}
		elseif ($skipFirst > 0)
		{
			$objArticlesStmt->limit(max($total, 1), $skipFirst);
		}

		$objArticles = $objArticlesStmt->execute();

		$this->Template->articles = $this->parseArticles($objArticles);
		$this->Template->archives = $this->news_archives;
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyList'];

    }

}

?>