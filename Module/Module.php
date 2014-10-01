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


namespace Psi\News4ward\Module;

abstract class Module extends \Module
{

	/**
	 * Return the meta fields of a news article as array
	 * @param array $arrArticle
	 * @return array
	 */
	protected function getMetaFields($arrArticle)
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
					$return['date'] = \Date::parse($GLOBALS['objPage']->dateFormat, $arrArticle['start']);
					break;

				case 'datetime':
					$return['datetime'] = \Date::parse($GLOBALS['objPage']->datimFormat, $arrArticle['start']);
					break;

				case 'author':
					if (strlen($arrArticle['author']))
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $arrArticle['author'];
					}
					break;
			}
		}

		return $return;
	}


	/**
	 * Parse one or more items and return them as array
	 *
	 * @param array $arrArticles
	 * @param bool|Template $objTemplate
	 * @return array
	 */
	protected function parseArticles($arrArticles, $objTemplate=false)
	{
		if (!$arrArticles)
		{
			return array();
		}

		global $objPage;
		$this->import('\String');
		$this->import('\News4ward\Helper','Helper');

		$limit = count($arrArticles);
		$count = 0;
		$arrReturn = array();

		foreach ($arrArticles as $article)
		{
			// init FrontendTemplate if theres no object given
			if (!$objTemplate)
			{
				$objTemplate = new \FrontendTemplate($this->news4ward_template);
			}
			$objTemplate->setData($article);

			$cssID = deserialize($article['cssID']);
			$objTemplate->count = ++$count;
			$objTemplate->class = ($cssID && strlen($cssID[1]) ? ' ' .$cssID[1] : '')
									. (($count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '')
									. ((($count % 2) == 0) ? ' odd' : ' even')
									. ($article['highlight'] ? ' highlight' : '');
			if($cssID && $cssID[0]) $cssID[0] = ' id="'.$cssID[0].'"';
			$objTemplate->link = $this->Helper->generateUrl($article);
			$objTemplate->archive = $article['archive'];

			// Clean the RTE output for the TEASER
			if ($article['teaser'] != '')
			{
				if ($objPage->outputFormat == 'xhtml')
				{
					$article['teaser'] = \String::toXhtml($article['teaser']);
				}
				else
				{
					$article['teaser'] = \String::toHtml5($article['teaser']);
				}

				$objTemplate->teaser = \String::encodeEmail($article['teaser']);
			}


			// Generate ContentElements
			$objContentelements = $this->Database->prepare('SELECT id FROM tl_content WHERE pid=? AND ptable="tl_news4ward_article" ' . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . ' ORDER BY sorting ')->execute($article['id']);
			$strContent = '';
			while ($objContentelements->next())
			{
				$strContent .= $this->getContentElement($objContentelements->id);
			}

			// Clean the RTE output for the CONTENT
			if ($strContent != '')
			{
				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$strContent = \String::toXhtml($strContent);
				}
				else
				{
					$strContent = \String::toHtml5($strContent);
				}

				$strContent = \String::encodeEmail($strContent);
			}

			$objTemplate->content = $strContent;


			// Add meta information
			$arrMeta = $this->getMetaFields($article);
			$objTemplate->date = $arrMeta['date'];
			$objTemplate->hasMetaFields = count($arrMeta) ? true : false;
			$objTemplate->timestamp = $article['start'];
			$objTemplate->author = $arrMeta['author'];
			$objTemplate->datetime = $arrMeta['datetime'];

			// Resolve ID from database driven filesystem
			if ($article['teaserImage'] && ($objImage = \FilesModel::findByPk($article['teaserImage'])) !== null)
			{
				$article['teaserImage'] = $objImage->path;
			}
			else
			{
                $article['teaserImage'] = '';
			}

			// Add teaser image
			if ($article['teaserImage'] && is_file(TL_ROOT.'/'.$article['teaserImage']))
			{
				$imgSize = deserialize($this->imgSize, true);
				$objTemplate->arrSize = $imgSize;
				if(count($imgSize)>1)
				{
					$objTemplate->teaserImage = \Image::get($article['teaserImage'], $imgSize[0], $imgSize[1], $imgSize[2]);
				}
				else
				{
					$objTemplate->teaserImage = $article['teaserImage'];
				}
				$objTemplate->teaserImageRaw = $objTemplate->teaserImag;
			} else {
                $objTemplate->teaserImage = '';
            }


			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['News4wardParseArticle']) && is_array($GLOBALS['TL_HOOKS']['News4wardParseArticle']))
			{
				foreach ($GLOBALS['TL_HOOKS']['News4wardParseArticle'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this, $article, $objTemplate, $arrArticles);
				}
			}

			$arrReturn[] = $objTemplate->parse();
		}

		return $arrReturn;
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

		$this->import('\FrontendUser', 'User');
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
