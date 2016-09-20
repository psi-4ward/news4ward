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
$GLOBALS['TL_LANG']['tl_news4ward_article']['title']       = array('Title', 'Please enter the blog entry title.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['subheadline'] = array('Subheadline', 'Here you can enter a subheadline.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['alias']       = array('Entry alias', 'The blog entry alias is a unique reference to the article which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['author']      = array('Author', 'Here you can change the author of the blog entry.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['pageTitle']   = array('Page title', 'Please enter the page title.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['keywords']    = array('Meta keywords', 'Here you can enter a comma separated list of keywords for this entry. Searche engines (Google, Yahoo,...) process in general max. 800 characters.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['description'] = array('Meta description', 'Here you can enter short description of this entry (Meta-Description).');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserCssID'] = array('Teaser CSS ID/class', 'Here you can set an ID and one or more classes for the teaser element.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaser']      = array('Teaser text', 'Teaser text is displayed in the list of entries.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImage'] = array('Teaser image', 'Teaser image is displayed in the list of entries.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImageCaption'] = array('Image caption', 'Here you can enter image caption.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['social']      = array('Syndication', 'Here you can choose which options are available.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['cssID']       = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['space']       = array('Space in front and after', 'Here you can enter the spacing in front of and after the article in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['status']      = array('Status', 'Publish the entry on the page.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['start']       = array('Show from', 'Do not show the blog entry on the website before this day.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['stop']        = array('Show until', 'Do not show the blog entry on the website on and after this day.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['tstamp']      = array('Revision date', 'Date and time of the latest revision.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight']   = array('Featured entry','Show the blog entry in a featured blog entries list.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky']      = array('Promoted entry','Entry will be visible on the top of entries list.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['useFacebookImage']= array('Use another image for Facebook', 'Here you can choose the image, which will be used instead of Teaser image.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['facebookImage']   = array('Facebook image', 'Here you can select the image, which will be used on Facebook.');

$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'] = array('published'=>'published','review'=>'review','draft'=>'draft');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news4ward_article']['title_legend']   = 'Title and Author';
$GLOBALS['TL_LANG']['tl_news4ward_article']['layout_legend']  = 'Meta-data for search engines';
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaser_legend']  = 'Teaser';
$GLOBALS['TL_LANG']['tl_news4ward_article']['expert_legend']  = 'Expert settings';
$GLOBALS['TL_LANG']['tl_news4ward_article']['publish_legend'] = 'Publish settings';
$GLOBALS['TL_LANG']['tl_news4ward_article']['facebook_legend'] = 'Facebook settings';
$GLOBALS['TL_LANG']['tl_news4ward_article']['print']          = 'Print the page';
$GLOBALS['TL_LANG']['tl_news4ward_article']['pdf']            = 'Export as PDF';
$GLOBALS['TL_LANG']['tl_news4ward_article']['facebook']       = 'Share on Facebooku';
$GLOBALS['TL_LANG']['tl_news4ward_article']['twitter']        = 'Share on Twitteri';
$GLOBALS['TL_LANG']['tl_news4ward_article']['google']         = 'Share on Google+';
$GLOBALS['TL_LANG']['tl_news4ward_article']['email']          = 'Send by e-mail';



/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news4ward_article']['new']        = array('New entry', 'Create a new entry');
$GLOBALS['TL_LANG']['tl_news4ward_article']['show']       = array('Entry details', 'Show the details of entry ID %s');
$GLOBALS['TL_LANG']['tl_news4ward_article']['edit']       = array('Edit entry', 'Edit entry ID %s');
$GLOBALS['TL_LANG']['tl_news4ward_article']['editheader'] = array('Edit entry settings', 'Edit entry settings ID %s');
$GLOBALS['TL_LANG']['tl_news4ward_article']['copy']       = array('Duplicate entry', 'Duplicate entry ID %s');
$GLOBALS['TL_LANG']['tl_news4ward_article']['cut']        = array('Move entry', 'Move entry ID %s');
$GLOBALS['TL_LANG']['tl_news4ward_article']['delete']     = array('Delete entry', 'Delete entry ID %s');
$GLOBALS['TL_LANG']['tl_news4ward_article']['toggle']     = array('Publish/unpublish entry', 'Publish/Unpublish entry ID %s');