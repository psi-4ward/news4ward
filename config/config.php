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
	'icon'    => 'system/modules/news4ward/html/icon.png'
);

// FE-Modules
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'news4ward' => array
	(
		'news4wardList'    => 'ModuleNews4wardList',
		'news4wardReader'  => 'ModuleNews4wardReader',
	)
));


// add news archive permissions
$GLOBALS['TL_PERMISSIONS'][] = 'news4ward';
$GLOBALS['TL_PERMISSIONS'][] = 'news4ward_newp';

// Register hook to add items to the indexer
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('News4wardHelper', 'getSearchablePages');