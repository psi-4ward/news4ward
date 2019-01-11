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

class Listing extends Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');

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
	    $whereValues = array();

		// news archives
		$where[] = 'tl_news4ward_article.pid IN('. implode(',', array_map('intval', $this->news_archives)) . ')';

		// published
		if (!BE_USER_LOGGED_IN)
		{
			$where[] = "(tl_news4ward_article.start='' OR tl_news4ward_article.start<".$time.") AND (tl_news4ward_article.stop='' OR tl_news4ward_article.stop>".$time.") AND tl_news4ward_article.status='published'";
		}

		// show only highlighted items?
		if ($this->news4ward_featured == 'featured')
			$where[] = 'tl_news4ward_article.highlight="1"';
		elseif ($this->news4ward_featured == 'unfeatured')
			$where[] = 'tl_news4ward_article.highlight<>"1"';

		// limit the time period
		if ($this->news4ward_timeConstraint != 'all' && $this->news4ward_timeConstraint != '')
		{
			list($strBegin, $strEnd) = $this->getDatesFromFormat(new \Date(), $this->news4ward_timeConstraint);
			$where[] = "tl_news4ward_article.start >= $strBegin AND tl_news4ward_article.start <= $strEnd";
		}

		// HOOK: add filter logic from other modules like tags
		if ($this->news4ward_ignoreFilters != '1' && isset($GLOBALS['TL_HOOKS']['News4wardListFilter']) && is_array($GLOBALS['TL_HOOKS']['News4wardListFilter']))
		{
			foreach ($GLOBALS['TL_HOOKS']['News4wardListFilter'] as $callback)
			{
				$this->import($callback[0]);
				$tmp = $this->{$callback[0]}->{$callback[1]}($this);

				if (is_string($tmp) && !empty($tmp))
					$where[] = $tmp;
				if(is_array($tmp))
				{
					$where[] = $tmp['where'];
					if(is_string($tmp['values'])) $whereValues[] = $tmp['values'];
					if(is_array($tmp['values'])) $whereValues = array_merge($whereValues, $tmp['values']);
				}
			}
		}


		/* Ordering */
		$ordering = array('tl_news4ward_article.sticky DESC');
		if($this->news4ward_order === 'random') {
		  $ordering[] = 'RAND()';
		} else {
          $ordering[] = 'tl_news4ward_article.'.$this->news4ward_order;
        }

		/* Pagination */
		$skipFirst = intval($this->news4ward_skipFirst);
		$offset = 0;
		$limit = null;

		// Maximum number of items
		if ($this->news4ward_numberOfItems > 0)	$limit = $this->news4ward_numberOfItems;

		// Get the total number of items
		$objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_news4ward_article WHERE ".implode(' AND ',$where))->execute($whereValues);
		$total = $objTotal->total - $skipFirst;

		// Split the results
		if ($this->news4ward_perPage > 0 && (!isset($limit) || $this->news4ward_numberOfItems > $this->news4ward_perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;

			// Check the maximum page number
			if ($page > ($total/$this->news4ward_perPage))
			{
				$page = ceil($total/$this->news4ward_perPage);
			}

			// Limit and offset
			$limit = $this->news4ward_perPage;
			$offset = (max($page, 1) - 1) * $this->news4ward_perPage;

			// Overall limit
			if ($offset + $limit > $total)
			{
				$limit = $total - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->news4ward_perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}


		/* get the items */
		$objArticlesStmt = $this->Database->prepare("
			SELECT tl_news4ward_article.*, author AS authorId, user.name as author, user.email as authorEmail,
				(SELECT title FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS archive,
				(SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=tl_news4ward_article.pid) AS parentJumpTo
			FROM tl_news4ward_article
			LEFT JOIN tl_user AS user ON (tl_news4ward_article.author=user.id)
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

		$objArticles = $objArticlesStmt->execute($whereValues);

	    $arrArticles = $objArticles->fetchAllAssoc();

		// overwrite parentJumpTo
		if ($this->news4ward_overwriteArchiveJumpTo)
		{
			foreach ($arrArticles as $k => $article)
			{
				$arrArticles[$k]['parentJumpTo'] = $this->jumpTo;
			}
		}

	    $this->Template->articles = $this->parseArticles($arrArticles);
		$this->Template->archives = $this->news_archives;
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyList'];


		// add newsfeeds to page
		$this->addNewsfeedsToLayout();
    }


	/**
	 * Add Newsfeeds links to the page
	 * @return void
	 */
	protected function addNewsfeedsToLayout()
	{
		if (!$GLOBALS['objPage']->layout)
		{
			$objLayout = $this->Database->prepare('SELECT news4ward_feeds FROM tl_layout WHERE fallback="1"')->limit(1)->execute();
		}
		else
		{
			$objLayout = $this->Database->prepare('SELECT news4ward_feeds FROM tl_layout WHERE id=?')->limit(1)->execute($GLOBALS['objPage']->layout);
		}

		if (!$objLayout->numRows) return;

		$arrNews4wardIDs = deserialize($objLayout->news4ward_feeds,true);
		if(empty($arrNews4wardIDs)) return;

		$objNews4ward = $this->Database->prepare('SELECT feedBase,alias,format,title FROM tl_news4ward WHERE FIND_IN_SET(id,?) AND makeFeed="1"')->execute(implode(',',$arrNews4wardIDs));
		if (!$objNews4ward->numRows) return;

		$strTagEnding = ($GLOBALS['objPage']->outputFormat == 'xhtml') ? ' />' : '>';

		while ($objNews4ward->next())
		{
			$base = strlen($objNews4ward->feedBase) ? $objNews4ward->feedBase : $this->Environment->base;
			$GLOBALS['TL_HEAD'][] = '<link rel="alternate" href="' . $base . $objNews4ward->alias . '.xml" type="application/' . $objNews4ward->format . '+xml" title="' . $objNews4ward->title . '"' . $strTagEnding . "\n";
		}

	}


	/**
	 * Return the begin and end timestamp
	 * @param \Date
	 * @param string
	 * @return array
	 */
	protected function getDatesFromFormat(\Date $objDate, $strFormat)
	{
		switch ($strFormat)
		{
			case 'cur_month':
				return array($objDate->monthBegin, $objDate->monthEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth']);
				break;

			case 'cur_year':
				return array($objDate->yearBegin, $objDate->yearEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyYear']);
				break;

			case 'all': // 1970-01-01 00:00:00 - 2038-01-01 00:00:00
				return array(0, 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_7':
				$objToday = new \Date();
				return array((strtotime('-7 days', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_14':
				$objToday = new \Date();
				return array((strtotime('-14 days', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_30':
				$objToday = new \Date();
				return array((strtotime('-1 month', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_90':
				$objToday = new \Date();
				return array((strtotime('-3 months', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_180':
				$objToday = new \Date();
				return array((strtotime('-6 months', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_365':
				$objToday = new \Date();
				return array((strtotime('-1 year', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_two':
				$objToday = new \Date();
				return array((strtotime('-2 years', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;
		}
	}
}
