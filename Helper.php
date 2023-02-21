<?php

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

namespace Psi\News4ward;

use Contao\System;
use Contao\StringUtil;

class Helper extends \Frontend
{

	/**
	 * Execute some ajax actions
	 * * toggle news4ward_article.status
	 *
	 * @param $strAction
	 * @return void
	 */
	public function ajaxHandler($strAction)
	{
		switch($strAction)
		{
			case 'news4wardArticleStatusToggle':
				$this->loadDataContainer('tl_news4ward_article');
				$this->import('BackendUser','User');
				$tl_news4ward_article = new \tl_news4ward_article();
				\Input::setGet('id', \Input::post('id'));

				// validation
				if(		TL_MODE != 'BE'
					|| 	!preg_match("~^\d+$~", \Input::post('id'))
					|| 	!in_array(\Input::post('status'), $GLOBALS['TL_DCA']['tl_news4ward_article']['fields']['status']['options'])
					|| 	!$this->User->hasAccess('tl_news4ward_article::status','alexf')
					||  $tl_news4ward_article->checkPermission()
					)
				{
					header('HTTP/1.0 400 Bad Request',true,400);
					exit;
				}

				$this->import('Database');
				$this->Database->prepare('UPDATE tl_news4ward_article SET status=? WHERE id=? LIMIT 1')
							   ->executeUncached(\Input::post('status'),\Input::post('id'));

			break;

			default: return;
		}

		exit;
	}


	/**
	 * Replace news4ward insert tags
	 * @param $strTag
	 * @return bool|string
	 */
	public function inserttagReplacer($strTag)
	{
		list($strTag, $strValue) = explode('::',$strTag);
		switch($strTag)
		{
			case 'news4ward':
				switch($strValue)
				{
					case 'filter_hint':
						if(!isset($GLOBALS['news4ward_filter_hint'])) return '';

						$tpl = new \FrontendTemplate('news4ward_filter_hint');
						$tpl->items = $GLOBALS['news4ward_filter_hint'];
						return $tpl->parse();
					break;

					default: return false; break;
				}

			break;

			case 'news4ward_link':
			case 'news4ward_open':
			case 'news4ward_url':
			case 'news4ward_title':
				$objArticle = $this->Database->prepare('
						SELECT a.id, a.alias, a.title, p.jumpTo as parentJumpTo
						FROM tl_news4ward_article AS a
						LEFT JOIN tl_news4ward AS p ON (a.pid = p.id)
						WHERE (a.id=? OR a.alias=?)'
					. (!BE_USER_LOGGED_IN ? "AND (a.start='' OR a.start<?) AND (a.stop='' OR a.stop>?) AND a.status='published'" : ""))
					->execute($strValue, $strValue, time(), time());

				if(!$objArticle->numRows) return '';

				if($strTag == 'news4ward_link')
				{
					return sprintf('<a href="%s" title="%s">%s</a>',$this->generateUrl($objArticle->row()), $objArticle->title, $objArticle->title);
				}
				else if($strTag == 'news4ward_open')
				{
					return sprintf('<a href="%s" title="%s">',$this->generateUrl($objArticle->row()), $objArticle->title);
				}
				else if($strTag == 'news4ward_url')
				{
					return $this->generateUrl($objArticle->row());
				}
				else if($strTag == 'news4ward_title')
				{
					return $objArticle->title;
				}

			break;


			default: return false; break;
		}

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
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
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
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND noSearch!=1")
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

					$arrProcessed[$objArchive->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), (($GLOBALS['TL_CONFIG']['useAutoItem'] && in_array('items', $GLOBALS['TL_AUTO_ITEM'])) ?  '/%s' : '/items/%s'), $objParent->language);
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
				$arrPages[] = $this->generateUrl($objArticle->row(), $strUrl);
			}
		}

		return $arrPages;
	}


	/**
	 * Return the link of a news article
	 *
	 * @param array $arrArticle
	 * @param bool|string $strUrl an optional predefined url
	 * @return string
	 */
	public function generateUrl($arrArticle, $strUrl=false)
	{
		$strParam = (($arrArticle['alias'] != '' && !isset($GLOBALS['TL_CONFIG']['disableAlias'])) ? $arrArticle['alias'] : $arrArticle['id']);

		if (!$GLOBALS['TL_CONFIG']['useAutoItem'] || !in_array('items', $GLOBALS['TL_AUTO_ITEM']))
		{
			$strParam = 'items/' . $strParam;
		}

		if ($strUrl)
		{
			return sprintf($strUrl, $strParam);
		}
		elseif ($arrArticle['parentJumpTo'] && ($objJumpTo = \PageModel::findByPk($arrArticle['parentJumpTo'])) !== null)
		{
		    return $objJumpTo->getFrontendUrl('/' . $strParam);
		}
		elseif (TL_MODE == 'FE')
		{
			return $this->generateFrontendUrl($GLOBALS['objPage']->row(), '/' . $strParam);
		}

		return '';
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
		if (\Input::get('act') == 'delete' || $objArchive->protected)
		{
			$this->import('Files');
			$this->Files->delete($objArchive->feedName . '.xml');
		}

		// Update XML file
		else
		{
			$this->generateFiles($objArchive->row());
			$this->log('Generated news4ward feed "' . $objArchive->feedName . '.xml"', 'Helper generateFeed()', TL_CRON);
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->import('Automator');
		$this->Automator->purgeXmlFiles();
		$objArchive = $this->Database->execute("SELECT * FROM tl_news4ward WHERE makeFeed=1 AND protected!=1");

		while ($objArchive->next())
		{
			$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news4ward' . $objArchive->id;

			$this->generateFiles($objArchive->row());
			$this->log('Generated news4ward feed "' . $objArchive->feedName . '.xml"', 'Helper generateFeeds()', TL_CRON);
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

		$objFeed = new \Feed($strFile);

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
		$strUrl = $this->generateFrontendUrl($objParent->row(), (($GLOBALS['TL_CONFIG']['useAutoItem'] && in_array('items', $GLOBALS['TL_AUTO_ITEM'])) ?  '/%s' : '/items/%s'), $objParent->language);

		// be sure to be absolute
		if (substr($strUrl,0,4) != 'http')
		{
			$strUrl = $strLink.$strUrl;
		}

		// Parse items
		while ($objArticle->next())
		{
			$objItem = new \FeedItem();

			$objItem->title = $objArticle->title;
			$objItem->link = $this->generateUrl($objArticle->row(), $strUrl);
			$objItem->published = $objArticle->start;
			$objItem->author = $objArticle->authorName;

			// Prepare the description
			if ($arrArchive['source'] == 'source_text')
			{
				/* generate the content-elements */
				$objContentelements = $this->Database->prepare('SELECT id FROM tl_content WHERE pid=? AND ptable="news4ward" AND invisible="" ORDER BY sorting')->execute($objArticle->id);
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
//			$strDescription = str_replace(array('<br>', '[-]', '&shy;', '[nbsp]', '&nbsp;'), array('<br/>', '', '', ' ', ' '), $strDescription);
//			$strDescription = preg_replace('~/<img([^>]+)>/g~', '<img$i />', $strDescription);
			$strDescription = \StringUtil::toXhtml($strDescription);
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
		if (class_exists(\Contao\CoreBundle\ContaoCoreBundle::class)) {
    			$objRss = new \File(StringUtil::stripRootDir(System::getContainer()->getParameter('contao.web_dir')) . '/share/' . $strFile . '.xml');
		} else {
    			$objRss = new \File($strFile . '.xml');
		}
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


	/**
	 * Restrict the path for all fileTree fields when the archive has a filePath restriction
	 * this method gets call through DC-onload_callback
	 *
	 * @param DataContainer $dc
	 */
	public function setFiletreePath($dc)
	{
		$objNews4ward = null;
		switch($dc->table)
		{
			case 'tl_news4ward_article':
				$objNews4wardArticle = $this->Database->prepare('SELECT pid FROM tl_news4ward_article WHERE id=?')->execute($dc->id);
				$objNews4ward = $this->Database->prepare('SELECT useFilePath, filePath FROM tl_news4ward WHERE id=?')->execute($objNews4wardArticle->pid);
			break;

			case 'tl_content':
				$objNews4wardArticle = $this->Database->prepare('SELECT a.pid FROM tl_content AS c LEFT JOIN tl_news4ward_article AS a ON (c.pid=a.id) WHERE c.id=?')->execute($dc->id);
				$objNews4ward = $this->Database->prepare('SELECT useFilePath, filePath FROM tl_news4ward WHERE id=?')->execute($objNews4wardArticle->pid);
			break;
		}

		if(!$objNews4ward || $objNews4ward->numRows <= 0 || $objNews4ward->useFilePath != '1') return;

		$objFile = \FilesModel::findByPk($objNews4ward->filePath);
		if(!$objFile) return;

		foreach($GLOBALS['TL_DCA'][$dc->table]['fields'] as $fld => $data)
		{
			if($data['inputType']!='fileTree') continue;
			$GLOBALS['TL_DCA'][$dc->table]['fields'][$fld]['eval']['path'] = $objFile->path;
		}
	}

}
