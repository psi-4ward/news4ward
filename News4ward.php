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

	protected static $objPageCache = array();

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

				case 'comments':
					$objComments = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_comments WHERE source='tl_news4ward_article' AND parent=?" . (!BE_USER_LOGGED_IN ? " AND published=1" : ""))
												  ->execute($objArticle->id);

					if ($objComments->numRows)
					{
						$return['ccount'] = $objComments->total;
						$return['comments'] = sprintf($GLOBALS['TL_LANG']['MSC']['commentCount'], $objComments->total);
					}
					break;
			}
		}

		return $return;
	}



	/**
	 * Parse one or more items and return them as array
	 * @param Database_Result $objArticles
	 * @return array
	 */
	protected function parseArticles(Database_Result $objArticles)
	{
		if ($objArticles->numRows < 1)
		{
			return array();
		}

		global $objPage;
		$this->import('String');

		$arrArticles = array();
		$limit = $objArticles->numRows;
		$count = 0;
		$imgSize = false;

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$imgSize = $this->imgSize;
			}
		}

		while ($objArticles->next())
		{
			$objTemplate = new FrontendTemplate($this->news4ward_template);
			$objTemplate->setData($objArticles->row());

			$objTemplate->count = ++$count;
			$objTemplate->class = (strlen($objArticles->cssClass) ? ' ' . $objArticles->cssClass : '') . (($count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even');
			$objTemplate->link = $this->generateUrl($objArticles);
			$objTemplate->archive = $objArticles->archive;

			// Clean the RTE output
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

			// Display the "read more" button for external/article links
			if (($objArticles->source == 'external' || $objArticles->source == 'article') && !strlen($objArticles->text))
			{
				$objTemplate->text = true;
			}

			// Encode e-mail addresses
			else
			{
				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$objArticles->text = $this->String->toXhtml($objArticles->text);
				}
				else
				{
					$objArticles->text = $this->String->toHtml5($objArticles->text);
				}

				$objTemplate->text = $this->String->encodeEmail($objArticles->text);
			}

			$arrMeta = $this->getMetaFields($objArticles);

			// Add meta information
			$objTemplate->date = $arrMeta['date'];
			$objTemplate->hasMetaFields = count($arrMeta) ? true : false;
			$objTemplate->numberOfComments = $arrMeta['ccount'];
			$objTemplate->commentCount = $arrMeta['comments'];
			$objTemplate->timestamp = $objArticles->date;
			$objTemplate->author = $arrMeta['author'];
			$objTemplate->datetime = date('Y-m-d\TH:i:sP', $objArticles->date);

			$arrArticles[] = $objTemplate->parse();
		}

		return $arrArticles;
	}


	/**
	 * Return the link of a news article
	 * @param Database_Result $objArticle
	 * @return string
	 */
	protected function generateUrl(Database_Result $objArticle)
	{
		if($objArticle->parentJumpTo)
		{
			if(!isset(self::$objPageCache[$objArticle->parentJumpTo]))
			{
				self::$objPageCache[$objArticle->parentJumpTo] = $this->Database->prepare('SELECT id,alias FROM tl_page WHERE id=?')->execute($objArticle->parentJumpTo);
			}
			return $this->generateFrontendUrl(self::$objPageCache[$objArticle->parentJumpTo]->row(), '/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id));
		}
		else
		{
			return $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id));
		}

		// Link to an article
		$objParent = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title, p.id, p.alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND a.id=?")
									->limit(1)
									->execute($objArticle->articleId);

		if ($objParent->numRows)
		{
			return $this->generateFrontendUrl($objParent->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objParent->aAlias)) ? $objParent->aAlias : $objParent->aId));
		}

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