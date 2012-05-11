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


class News4wardHelper extends Frontend
{
	protected static $objPageCache = array();


	/**
	 * Replace news4ward insert tags
	 * @param $strTag
	 * @return bool|string
	 */
	public function inserttagReplacer($strTag)
	{
		if (substr($strTag,0,9) == 'news4ward')
		{
			list($strTag,$strValue) = explode('::',$strTag);
			switch($strValue)
			{
				case 'filter_hint':
					if(!isset($GLOBALS['news4ward_filter_hint'])) return '';

					$tpl = new FrontendTemplate('news4ward_filter_hint');
					$tpl->items = $GLOBALS['news4ward_filter_hint'];
					return $tpl->parse();
				break;

				default:
					return false;
				break;
			}
		}

		return false;
	}


	/**
	 * Add items to the indexer
	 * @param array
	 * @param integer
	 * @param boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page');
		}

		$time = time();
		$arrProcessed = array();

		// Get all news archives
		$objArchive = $this->Database->execute("SELECT id, jumpTo FROM tl_news4ward WHERE protected!=1");

		// Walk through each archive
		while ($objArchive->next())
		{
			if (!empty($arrRoot) && !in_array($objArchive->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get the URL of the jumpTo page
			if (!isset($arrProcessed[$objArchive->jumpTo]))
			{
				$arrProcessed[$objArchive->jumpTo] = false;

				// Get the target page
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND noSearch!=1" . ($blnIsSitemap ? " AND sitemap!='map_never'" : ""))
											->limit(1)
											->execute($objArchive->jumpTo);

				// Determin domain
				if ($objParent->numRows)
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objParent->id);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objArchive->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}
			}

			// Skip items without target page
			if ($arrProcessed[$objArchive->jumpTo] === false)
			{
				continue;
			}

			$strUrl = $arrProcessed[$objArchive->jumpTo];

			// Get items
			$objArticle = $this->Database->prepare("SELECT tl_news4ward_article.id, tl_news4ward_article.alias, tl_news4ward.jumpTo as parentJumpTo
			 										FROM tl_news4ward_article
			 										LEFT JOIN tl_news4ward ON (tl_news4ward.id=tl_news4ward_article.pid)
			 										WHERE tl_news4ward_article.pid=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND status='published'
			 										ORDER BY start DESC")
										 ->execute($objArchive->id);

			// Add items to the indexer
			while ($objArticle->next())
			{
				$arrPages[] = $this->generateUrl($objArticle, $strUrl);
			}
		}

		return $arrPages;
	}


	/**
	 * Return the link of a news article
	 *
	 * @param Database_Result $objArticle
	 * @param bool|string $strUrl an optional predefined url
	 * @return string
	 */
	public function generateUrl(Database_Result $objArticle, $strUrl=false)
	{
		if($strUrl)
		{
			return sprintf($strUrl, (($objArticle->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
		}
		elseif($objArticle->parentJumpTo)
		{
			if(!isset(self::$objPageCache[$objArticle->parentJumpTo]))
			{
				self::$objPageCache[$objArticle->parentJumpTo] = $this->Database->prepare('SELECT id,alias FROM tl_page WHERE id=?')->execute($objArticle->parentJumpTo);
			}
			return $this->generateFrontendUrl(self::$objPageCache[$objArticle->parentJumpTo]->row(), '/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id));
		}
		elseif(TL_MODE == 'FE')
		{
			return $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objArticle->alias)) ? $objArticle->alias : $objArticle->id));
		}

	}



	/**
	 * Update a particular RSS feed
	 * @param integer
	 */
	public function generateFeed($intId)
	{
		$objArchive = $this->Database->prepare("SELECT * FROM tl_news4ward WHERE id=? AND makeFeed=?")
									 ->limit(1)
									 ->execute($intId, 1);

		if ($objArchive->numRows < 1)
		{
			return;
		}

		$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news4ward' . $objArchive->id;

		// Delete XML file
		if ($this->Input->get('act') == 'delete' || $objArchive->protected)
		{
			$this->import('Files');
			$this->Files->delete($objArchive->feedName . '.xml');
		}

		// Update XML file
		else
		{
			$this->generateFiles($objArchive->row());
			$this->log('Generated news4ward feed "' . $objArchive->feedName . '.xml"', 'News4wardHelper generateFeed()', TL_CRON);
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->removeOldFeeds();
		$objArchive = $this->Database->execute("SELECT * FROM tl_news4ward WHERE makeFeed=1 AND protected!=1");

		while ($objArchive->next())
		{
			$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news4ward' . $objArchive->id;

			$this->generateFiles($objArchive->row());
			$this->log('Generated news4ward feed "' . $objArchive->feedName . '.xml"', 'News4wardHelper generateFeeds()', TL_CRON);
		}
	}


	/**
	 * Generate an XML files and save them to the root directory
	 * @param array
	 */
	protected function generateFiles($arrArchive)
	{
		$time = time();
		$strType = ($arrArchive['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strLink = ($arrArchive['feedBase'] != '') ? $arrArchive['feedBase'] : $this->Environment->base;
		$strFile = $arrArchive['feedName'];

		$objFeed = new Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get items
		$objArticleStmt = $this->Database->prepare("SELECT *, (SELECT name FROM tl_user u WHERE u.id=n.author) AS authorName
													FROM tl_news4ward_article n
													WHERE pid=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND status='published'
													ORDER BY start DESC");

		if ($arrArchive['maxItems'] > 0)
		{
			$objArticleStmt->limit($arrArchive['maxItems']);
		}

		$objArticle = $objArticleStmt->execute($arrArchive['id']);

		// Get the default URL
		$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									->limit(1)
									->execute($arrArchive['jumpTo']);

		if ($objParent->numRows < 1)
		{
			return;
		}

		$objParent = $this->getPageDetails($objParent->id);
		$strUrl = $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);

		// Parse items
		while ($objArticle->next())
		{
			$objItem = new FeedItem();

			$objItem->title = $objArticle->title;
			$objItem->link = $this->generateUrl($objArticle, $strUrl);
			$objItem->published = $objArticle->start;
			$objItem->author = $objArticle->authorName;

			// Prepare the description
			if($arrArchive['source'] == 'source_text')
			{
				/* generate the content-elements */
				$objContentelements = $this->Database->prepare('SELECT id FROM tl_content WHERE pid=? AND do="news4ward" AND invisible="" ORDER BY sorting')->execute($objArticle->id);
				$strDescription = '';
				while($objContentelements->next())
				{
					$strDescription .= $this->getContentElement($objContentelements->id);
				}
			}
			else
			{
				$strDescription = $objArticle->teaser;
			}
			$strDescription = $this->replaceInsertTags($strDescription);
			$objItem->description = $this->convertRelativeUrls($strDescription, $strLink);


			// Add the article image as enclosure
			if ($objArticle->addImage)
			{
				$objItem->addEnclosure($objArticle->singleSRC);
			}

			// Enclosure
			if ($objArticle->addEnclosure)
			{
				$arrEnclosure = deserialize($objArticle->enclosure, true);

				if (is_array($arrEnclosure))
				{
					foreach ($arrEnclosure as $strEnclosure)
					{
						if (is_file(TL_ROOT . '/' . $strEnclosure))
						{
							$objItem->addEnclosure($strEnclosure);
						}
					}
				}
			}

			$objFeed->addItem($objItem);
		}

		// Create file
		$objRss = new File($strFile . '.xml');
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


}