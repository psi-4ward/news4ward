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
$GLOBALS['TL_LANG']['tl_news4ward']['title']          = array('Title', 'Please enter a blog archive title.');
$GLOBALS['TL_LANG']['tl_news4ward']['jumpTo']         = array('Redirect page - Reader', 'Please choose the blog entries reader page to which visitors will be redirected when clicking a blog entry.');
$GLOBALS['TL_LANG']['tl_news4ward']['jumpToList']     = array('Redirect page - List', 'Please choose the page with corresponding entries list of this archive.');
$GLOBALS['TL_LANG']['tl_news4ward']['protected']      = array('Protect archive', 'Show blog entries to certain member groups only.');
$GLOBALS['TL_LANG']['tl_news4ward']['groups']         = array('Allowed member groups', 'These groups will be able to see the blog entries in this archive.');
$GLOBALS['TL_LANG']['tl_news4ward']['makeFeed']       = array('Create feed', 'Generate RSS or Atom feed from blog entries.');
$GLOBALS['TL_LANG']['tl_news4ward']['format']         = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_news4ward']['language']       = array('Feed language', 'Please enter the feed language according to the ISO-639 standard (e.g. <em>en</em> or <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_news4ward']['source']         = array('Export settings', 'Here you can choose what will be exported.');
$GLOBALS['TL_LANG']['tl_news4ward']['maxItems']       = array('Maximum number of items', 'Here you can limit the number of feed items. Set to 0 to export all.');
$GLOBALS['TL_LANG']['tl_news4ward']['feedBase']       = array('Base URL', 'Please enter the base URL with protocol (e.g. http://).');
$GLOBALS['TL_LANG']['tl_news4ward']['alias']          = array('Feed alias', 'Here you can enter a unique filename (without extension). The XML feed file will be auto-generated in the <em>share</em> directory of your Contao installation, e.g. as <em>share/name.xml</em>.');
$GLOBALS['TL_LANG']['tl_news4ward']['description']    = array('Feed description', 'Here you can enter a short description of the blog feed.');
$GLOBALS['TL_LANG']['tl_news4ward']['tstamp']         = array('Revision date', 'Date and time of the latest revision.');
$GLOBALS['TL_LANG']['tl_news4ward']['useFilePath']    = array('Specify folder', 'Here you can define the file folder for items of this archive.');
$GLOBALS['TL_LANG']['tl_news4ward']['filePath']       = array('File folder of this archive', 'Select the folder which you want to attached to this archive.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news4ward']['title_legend']     = 'Title and redirection';
$GLOBALS['TL_LANG']['tl_news4ward']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_news4ward']['feed_legend']      = 'RSS/Atom feed';
$GLOBALS['TL_LANG']['tl_news4ward']['filePath_legend']  = 'Archive folder';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news4ward']['notify_admin']  = 'System administrator';
$GLOBALS['TL_LANG']['tl_news4ward']['notify_author'] = 'Author of the blog entry';
$GLOBALS['TL_LANG']['tl_news4ward']['notify_both']   = 'Author and system administrator';
$GLOBALS['TL_LANG']['tl_news4ward']['source_teaser'] = 'Teaser text';
$GLOBALS['TL_LANG']['tl_news4ward']['source_text']   = 'Complete entry';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news4ward']['new']        = array('New archive', 'Create a new archive');
$GLOBALS['TL_LANG']['tl_news4ward']['show']       = array('Archive details', 'Show the details of archive ID %s');
$GLOBALS['TL_LANG']['tl_news4ward']['edit']       = array('Edit archive', 'Edit archive ID %s');
$GLOBALS['TL_LANG']['tl_news4ward']['editheader'] = array('Edit archive settings', 'Edit the settings of archive ID %s');
$GLOBALS['TL_LANG']['tl_news4ward']['copy']       = array('Duplicate archive', 'Duplicate archive ID %s');
$GLOBALS['TL_LANG']['tl_news4ward']['delete']     = array('Delete archive', 'Delete archive ID %s');

?>