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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['news4ward_archives']      = array('Blog archives', 'Please select one or more blog archives.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_featured']      = array('Featured entries', 'Here you can choose how featured entries are handled.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_perPage']      = array('Entries per page', 'The number of entries per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_skipFirst']      = array('Skip entries', 'Here you can define how many entries will be skipped.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_numberOfItems'] = array('Number of entries', 'Here you can limit the number of entries. Set to 0 to show all.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_jumpToCurrent'] = array('No period selected', 'Here you can define what to display if no period is selected.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_metaFields']    = array('Meta fields', 'Here you can select the meta fields.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_template']      = array('Entries list template', 'Here you can select the blog entries list template.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_readerTemplate'] = array('Entries reader template', 'Here you can select the blog entries reader template.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_format']        = array('Archive format', 'Here you can choose the news archive format.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_startDay']      = array('Week start day', 'Here you can choose the week start day.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_order']         = array('Sort order', 'Here you can choose the sort order.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_showQuantity']  = array('Entries per page', 'The number of entries per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_facebookMeta']  = array('Create meta-data for Facebook', 'Facebook meta-data are atributes, which are used/shared on Facebook. What more Teaser image will be used for Facebook.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_filterHint']  	= array('Current filter indicator', 'The indicator and currently used/selected filter is shown on page by Insert-Tag {{news4ward::filter_hint}}. The value of this field (e.g. <em>Category:</em>) describes the actual filter (e.g. <em>Sport</em>). On the page will be visible <em>Category: Sport</em>.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_overwriteArchiveJumpTo']  	= array('Overwrite the archive jumpto page', 'Choose a separate reader page.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_ignoreFilters']  	= array('Ignore filter', 'This item-list ignores all filters.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news4ward_day']     = 'Day';
$GLOBALS['TL_LANG']['tl_module']['news4ward_month']   = 'Month';
$GLOBALS['TL_LANG']['tl_module']['news4ward_year']    = 'Year';
$GLOBALS['TL_LANG']['tl_module']['hide_module']  = 'Hide module';
$GLOBALS['TL_LANG']['tl_module']['show_current'] = 'Jump to the current period';
$GLOBALS['TL_LANG']['tl_module']['all_items']    = 'Show all blog entries';
$GLOBALS['TL_LANG']['tl_module']['featured']     = 'Show featured blog entries only';
$GLOBALS['TL_LANG']['tl_module']['unfeatured']   = 'Skip featured blog entries';

$GLOBALS['tl_module']['news4ward_order_ref']	= array
(
	'start ASC'		=> 'start ASC',
	'start DESC'	=> 'start DESC',
	'title ASC'		=> 'title ASC',
	'title DESC'	=> 'title DESC'
);