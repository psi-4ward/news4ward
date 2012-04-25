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
$GLOBALS['TL_LANG']['tl_news4ward']['title']          = array('Titel', 'Bitte geben Sie den Archiv-Titel ein.');
$GLOBALS['TL_LANG']['tl_news4ward']['jumpTo']         = array('Weiterleitungsseite', 'Bitte wählen Sie die Nachrichtenleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Beitrag anklicken.');
$GLOBALS['TL_LANG']['tl_news4ward']['protected']      = array('Archiv schützen', 'Nachrichten nur bestimmten Frontend-Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_news4ward']['groups']         = array('Erlaubte Mitgliedergruppen', 'Diese Mitgliedergruppen können die Nachrichten des Archivs sehen.');
$GLOBALS['TL_LANG']['tl_news4ward']['makeFeed']       = array('Feed erstellen', 'Einen RSS- oder Atom-Feed aus dem Nachrichtenarchiv generieren.');
$GLOBALS['TL_LANG']['tl_news4ward']['format']         = array('Feed-Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_news4ward']['language']       = array('Feed-Sprache', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639 Standards ein (z.B. <em>de</em>, <em>de-ch</em>).');
$GLOBALS['TL_LANG']['tl_news4ward']['source']         = array('Export-Einstellungen', 'Hier können Sie festlegen, was exportiert werden soll.');
$GLOBALS['TL_LANG']['tl_news4ward']['maxItems']       = array('Maximale Anzahl an Beiträgen', 'Hier können Sie die Anzahl der Beiträge limitieren. Geben Sie 0 ein, um alle zu exportieren.');
$GLOBALS['TL_LANG']['tl_news4ward']['feedBase']       = array('Basis-URL', 'Bitte geben Sie die Basis-URL mit Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_news4ward']['alias']          = array('Feed-Alias', 'Hier können Sie einen eindeutigen Dateinamen (ohne Endung) eingeben. Die XML-Datei wird automatisch im Wurzelverzeichnis Ihrer Contao-Installation erstellt, z.B. als <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_news4ward']['description']    = array('Feed-Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Nachrichten-Feeds ein.');
$GLOBALS['TL_LANG']['tl_news4ward']['tstamp']         = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news4ward']['title_legend']     = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_news4ward']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_news4ward']['feed_legend']      = 'RSS/Atom-Feed';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news4ward']['notify_admin']  = 'Systemadministrator';
$GLOBALS['TL_LANG']['tl_news4ward']['notify_author'] = 'Autor des Beitrags';
$GLOBALS['TL_LANG']['tl_news4ward']['notify_both']   = 'Autor und Systemadministrator';
$GLOBALS['TL_LANG']['tl_news4ward']['source_teaser'] = 'Teasertexte';
$GLOBALS['TL_LANG']['tl_news4ward']['source_text']   = 'Komplette Beiträge';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news4ward']['new']        = array('Neues Archiv', 'Ein neues Archiv erstellen');
$GLOBALS['TL_LANG']['tl_news4ward']['show']       = array('Archivdetails', 'Die Details des Archivs ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news4ward']['edit']       = array('Beiträge bearbeiten', 'Beiträge des Archivs ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news4ward']['editheader'] = array('Archiv-Einstellungen bearbeiten', 'Einstellungen des Archivs ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news4ward']['copy']       = array('Archiv duplizieren', 'Archiv ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news4ward']['delete']     = array('Archiv löschen', 'Archiv ID %s löschen');

?>