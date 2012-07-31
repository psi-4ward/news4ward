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
abstract class News4ward extends Module
{

	/**
	 * Return the meta fields of a news article as array
	 * @param Database_Result $objArticle
	 * @return array
	 */
	protected function getMetaFields(Database_Result $objArticle)
	{
		$meta = deserialize($this->news4ward_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'date':
					$return['date'] = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objArticle->start);
					break;

				case 'author':
					if (strlen($objArticle->author))
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objArticle->author;
					}
					break;
			}
		}

		return $return;
	}


	/**
	 * Parse one or more items and return them as array
	 *
	 * @param Database_Result $objArticles
	 * @param bool|Template $objTemplate
	 * @return array
	 */
	protected function parseArticles(Database_Result $objArticles, $objTemplate=false)
	{
		if ($objArticles->numRows < 1)
		{
			return array();
		}

		global $objPage;
		$this->import('String');
		$this->import('News4wardHelper');

		$arrArticles = array();
		$limit = $objArticles->numRows;
		$count = 0;

		while ($objArticles->next())
		{
			// init FrontendTemplate if theres no object given
			if(!$objTemplate)
			{
				$objTemplate = new FrontendTemplate($this->news4ward_template);
			}
			$objTemplate->setData($objArticles->row());

			$objTemplate->count = ++$count;
			$objTemplate->class = (strlen($objArticles->cssClass) ? ' ' . $objArticles->cssClass : '')
									. (($count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '')
									. ((($count % 2) == 0) ? ' odd' : ' even')
									. ($objArticles->highlight ? ' highlight' : '');
			$objTemplate->link = $this->News4wardHelper->generateUrl($objArticles);
			$objTemplate->archive = $objArticles->archive;

			// Clean the RTE output for the TEASER
			if ($objArticles->teaser != '')
			{
				if ($objPage->outputFormat == 'xhtml')
				{
					$objArticles->teaser = $this->String->toXhtml($objArticles->teaser);
				}
				else
				{
					$objArticles->teaser = $this->String->toHtml5($objArticles->teaser);
				}

				$objTemplate->teaser = $this->String->encodeEmail($objArticles->teaser);
			}


			// Generate ContentElements
			$objContentelements = $this->Database->prepare('SELECT id FROM tl_content WHERE pid=? AND do="news4ward" ' . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . ' ORDER BY sorting ')->execute($objArticles->id);
			$strContent = '';
			while($objContentelements->next())
			{
				$strContent .= $this->getContentElement($objContentelements->id);
			}

			// Clean the RTE output for the CONTENT
			if ($strContent != '')
			{
				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$strContent = $this->String->toXhtml($strContent);
				}
				else
				{
					$strContent = $this->String->toHtml5($strContent);
				}

				$strContent = $this->String->encodeEmail($strContent);
			}

			$objTemplate->content = $strContent;


			// Add meta information
			$arrMeta = $this->getMetaFields($objArticles);
			$objTemplate->date = $arrMeta['date'];
			$objTemplate->hasMetaFields = count($arrMeta) ? true : false;
			$objTemplate->timestamp = $objArticles->start;
			$objTemplate->author = $arrMeta['author'];
			$objTemplate->datetime = date('Y-m-d\TH:i:sP', $objArticles->start);

			// Add teaser image
			if($objArticles->teaserImage && is_file(TL_ROOT.'/'.$objArticles->teaserImage))
			{
				$imgSize = deserialize($this->imgSize,true);
				$objTemplate->arrSize = $imgSize;
				if(count($imgSize)>1)
				{
					$objTemplate->teaserImage = $this->getImage($objArticles->teaserImage,$imgSize[0],$imgSize[1],$imgSize[2]);
				}
				else
				{
					$objTemplate->teaserImage = $objArticles->teaserImage;
				}
				$objTemplate->teaserImageRaw = $objTemplate->teaserImag;
			}


			// HOOK: add custom logic
			if(isset($GLOBALS['TL_HOOKS']['News4wardParseArticle']) && is_array($GLOBALS['TL_HOOKS']['News4wardParseArticle']))
			{
				foreach ($GLOBALS['TL_HOOKS']['News4wardParseArticle'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this,$objArticles,$objTemplate);
				}
			}

			$arrArticles[] = $objTemplate->parse();
		}

		return $arrArticles;
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