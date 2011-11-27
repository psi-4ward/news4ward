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
$GLOBALS['TL_LANG']['tl_news4ward']['allowComments']  = array('Kommentare aktivieren', 'Besuchern das Kommentieren von Nachrichtenbeiträgen erlauben.');
$GLOBALS['TL_LANG']['tl_news4ward']['notify']         = array('Benachrichtigung an', 'Bitte legen Sie fest, wer beim Hinzufügen neuer Kommentare benachrichtigt wird.');
$GLOBALS['TL_LANG']['tl_news4ward']['sortOrder']      = array('Sortierung', 'Standardmäßig werden Kommentare aufsteigend sortiert, beginnend mit dem ältesten.');
$GLOBALS['TL_LANG']['tl_news4ward']['perPage']        = array('Kommentare pro Seite', 'Anzahl an Kommentaren pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_news4ward']['moderate']       = array('Kommentare moderieren', 'Kommentare erst nach Bestätigung auf der Webseite veröffentlichen.');
$GLOBALS['TL_LANG']['tl_news4ward']['bbcode']         = array('BBCode erlauben', 'Besuchern das Formatieren ihrer Kommentare mittels BBCode erlauben.');
$GLOBALS['TL_LANG']['tl_news4ward']['requireLogin']   = array('Login zum Kommentieren benötigt', 'Nur angemeldeten Benutzern das Erstellen von Kommentaren erlauben.');
$GLOBALS['TL_LANG']['tl_news4ward']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option nur, wenn das Erstellen von Kommentaren auf authentifizierte Benutzer beschränkt ist.');
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
$GLOBALS['TL_LANG']['tl_news4ward']['categories']     = array('Kagegorien', 'Diese Kategorien stehen in einem Betrag zur Auswahl');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news4ward']['title_legend']     = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_news4ward']['comments_legend']  = 'Kommentare';
$GLOBALS['TL_LANG']['tl_news4ward']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_news4ward']['categories_legend'] = 'Kategorien';
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
$GLOBALS['TL_LANG']['tl_news4ward']['edit']       = array('Archiv bearbeiten', 'Archiv ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news4ward']['editheader'] = array('Archiv-Einstellungen bearbeiten', 'Einstellungen des Archivs ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news4ward']['copy']       = array('Archiv duplizieren', 'Archiv ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news4ward']['delete']     = array('Archiv löschen', 'Archiv ID %s löschen');
$GLOBALS['TL_LANG']['tl_news4ward']['comments']   = array('Kommentare', 'Kommentare des Archivs ID %s anzeigen');