<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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

// BE-Module
$GLOBALS['BE_MOD']['content']['news4ward'] = array(
	'tables'  => array('tl_news4ward','tl_news4ward_article','tl_content'),
	'icon'    => 'system/modules/news4ward/html/icon.png',
	'javascript' => 'system/modules/news4ward/html/News4ward.js',
	'stylesheet' => 'system/modules/news4ward/html/News4ward.css',
);

// FE-Modules
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'news4ward' => array
	(
		'news4wardList'    => '\News4ward\Module\Listing',
		'news4wardReader'  => '\News4ward\Module\Reader',
	)
));


// add news archive permissions
$GLOBALS['TL_PERMISSIONS'][] = 'news4ward';
$GLOBALS['TL_PERMISSIONS'][] = 'news4ward_newp';
$GLOBALS['TL_PERMISSIONS'][] = 'news4ward_itemRights';

// Register auto_item
$GLOBALS['TL_AUTO_ITEM'][] = 'items';

// Register hook to add items to the indexer
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('\News4ward\Helper', 'getSearchablePages');

// Cronjob for feed generation
$GLOBALS['TL_CRON']['daily'][] = array('Helper', 'generateFeeds');

// hook for custom inserttags
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\News4ward\Helper', 'inserttagReplacer');

if (TL_MODE == 'BE')
{
	// hook for ajax requests
	$GLOBALS['TL_HOOKS']['executePreActions'][] = array('\News4ward\Helper', 'ajaxHandler');
}