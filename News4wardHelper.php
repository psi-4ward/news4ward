<?php if(!defined('TL_ROOT')) {die('You cannot access this file directly!');
}

/**
 * @copyright 4ward.media 2012 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */
 
class News4wardHelper extends Frontend
{
	protected static $objPageCache = array();


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


}