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
$GLOBALS['TL_LANG']['tl_news4ward_article']['title']       = array('Titel', 'Bitte geben Sie den Beitrag-Titel ein.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['subheadline'] = array('Unterüberschrift', 'Hier kann eine Unterüberschrift angegeben werden.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['alias']       = array('Beitragalias', 'Der Beitragalias ist eine eindeutige Referenz, die anstelle der numerischen Beitrag-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['author']      = array('Autor', 'Hier können Sie den Autor des Beitrags ändern.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['keywords']    = array('Suchbegriffe', 'Hier können Sie eine Liste kommagetrennter Suchbegriffe eingeben, die von Suchmaschinen wie Google oder Yahoo ausgewertet werden. Suchmaschinen indizieren normalerweise bis zu 800 Zeichen.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['description'] = array('Beschreibung', 'Hier können Sie die Meta-Description eingeben.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserCssID'] = array('Teaser-CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen für das Teaser-Element eingeben.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaser']      = array('Teasertext', 'Der Teasertext kann auch mit dem Inhaltselement "Beitragteaser" dargestellt werden.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImage'] = array('Teaserbild', 'Das Teaserbild kann in der Listenansicht dargestellt werden.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImageCaption'] = array('Bildunterschrift', 'Geben Sie eine Unterschrift für das Bild an.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['social']      = array('Syndikation', 'Hier legen Sie fest, welche Optionen verfügbar sind.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['cssID']       = array('CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['space']       = array('Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Beitrag in Pixeln eingeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['status']      = array('Status', 'Den Beitrag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['start']       = array('Anzeigen ab', 'Den Beitrag erst ab diesem Tag auf der Webseite anzeigen. Dieser Wert wird als Veröffentlichungsdatum genutzt.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['stop']        = array('Anzeigen bis', 'Den Beitrag nur bis zu diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['tstamp']      = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight']   = array('Beitrag hervorheben','Dieser Beitrag wird hervorgehoben.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky']      = array('Beitrag obenhalten','Dieser Beitrag wird bei der Sortierung nach oben gestellt.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['useFacebookImage']= array('Abweichendes Facebook-Bild verwenden', 'Hier können Sie ein Bild angeben, welches anstelle des Teaser-Bildes bei Facebook verlinkungen verwendet wird.');
$GLOBALS['TL_LANG']['tl_news4ward_article']['facebookImage']   = array('Facebook-Bild', 'Wählen Sie hier das Bild für die Facebook-Verlinkungen.');

$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'] = array('published'=>'veröffentlicht','review'=>'Review ausstehend','draft'=>'Entwurf');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news4ward_article']['title_legend']   = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_news4ward_article']['layout_legend']  = 'Suchbegriffe';
$GLOBALS['TL_LANG']['tl_news4ward_article']['teaser_legend']  = 'Teasertext';
$GLOBALS['TL_LANG']['tl_news4ward_article']['expert_legend']  = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_news4ward_article']['publish_legend'] = 'Veröffentlichung';
$GLOBALS['TL_LANG']['tl_news4ward_article']['facebook_legend'] = 'Facebook Einstellungen';
$GLOBALS['TL_LANG']['tl_news4ward_article']['print']          = 'Seite drucken';
$GLOBALS['TL_LANG']['tl_news4ward_article']['pdf']            = 'Beitrag als PDF';
$GLOBALS['TL_LANG']['tl_news4ward_article']['facebook']       = 'Auf Facebook teilen';
$GLOBALS['TL_LANG']['tl_news4ward_article']['twitter']        = 'Auf Twitter teilen';
$GLOBALS['TL_LANG']['tl_news4ward_article']['google']         = 'Auf Google+ teilen';
$GLOBALS['TL_LANG']['tl_news4ward_article']['email']          = 'Per E-Mail verschicken';



/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news4ward_article']['new']        = array('Neuer Beitrag', 'Einen neuen Beitrag anlegen');
$GLOBALS['TL_LANG']['tl_news4ward_article']['show']       = array('Beitragdetails', 'Details des Beitrags ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news4ward_article']['edit']       = array('Beitrag bearbeiten', 'Beitrag ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news4ward_article']['editheader'] = array('Beitrageinstellungen bearbeiten', 'Einstellungen des Beitrags ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news4ward_article']['copy']       = array('Beitrag duplizieren', 'Beitrag ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news4ward_article']['cut']        = array('Beitrag verschieben', 'Beitrag ID %s verschieben');
$GLOBALS['TL_LANG']['tl_news4ward_article']['delete']     = array('Beitrag löschen', 'Beitrag ID %s löschen');
$GLOBALS['TL_LANG']['tl_news4ward_article']['toggle']     = array('Beitrag veröffentlichen/unveröffentlichen', 'Beitrag ID %s veröffentlichen/unveröffentlichen');