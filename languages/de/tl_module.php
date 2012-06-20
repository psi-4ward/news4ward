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
$GLOBALS['TL_LANG']['tl_module']['news4ward_archives']      = array('Beitragsarchive', 'Bitte wählen Sie ein oder mehrere Beitragsarchive.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_featured']      = array('Hervorgehobene Beiträge', 'Hier legen Sie fest, wie hervorgehobene Beiträge gehandhabt werden.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_numberOfItems'] = array('Gesamtzahl der Beiträge', 'Hier können Sie die Gesamtzahl der Beiträge festlegen. Geben Sie 0 ein, um alle anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_jumpToCurrent'] = array('Kein Zeitraum ausgewählt', 'Hier legen Sie fest, was angezeigt wird, wenn kein Zeitraum ausgewählt ist.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_metaFields']    = array('Meta-Felder', 'Hier können Sie die Meta-Felder auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_template']      = array('Beitragtemplate', 'Hier können Sie das Beitragtemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_readerTemplate'] = array('Leser-Template', 'Hier können Sie das Leser-Template auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_format']        = array('Archivformat', 'Hier können Sie das Archivformat auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_startDay']      = array('Erster Wochentag', 'Hier können Sie den ersten Tag der Woche festlegen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_order']         = array('Sortierreihenfolge', 'Hier können Sie die Sortierreihenfolge festlegen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_showQuantity']  = array('Anzahl der Beiträge', 'Die Anzahl der Beiträge pro Seite.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_facebookMeta']  = array('Facebook Metadaten erzeugen', 'Die Facebook Metadaten sind Attribute, die beim Sharen oder Teilen verwendet werden. Unter anderem wird das Teaserbild für Facebook verwendet.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_filterHint']  	= array('Filter Hinweis', 'Ist der Filter dieses Modul gesetzt kann dieser über den Insert-Tag {{news4ward::filter_hint}} nochmals ausgegeben werden. Der Wert dieses Feldes beschreibt die Filtereigenschaft.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news4ward_day']     = 'Tag';
$GLOBALS['TL_LANG']['tl_module']['news4ward_month']   = 'Monat';
$GLOBALS['TL_LANG']['tl_module']['news4ward_year']    = 'Jahr';
$GLOBALS['TL_LANG']['tl_module']['hide_module']  = 'Das Modul ausblenden';
$GLOBALS['TL_LANG']['tl_module']['show_current'] = 'Zum aktuellen Zeitraum springen';
$GLOBALS['TL_LANG']['tl_module']['all_items']    = 'Alle Beiträge anzeigen';
$GLOBALS['TL_LANG']['tl_module']['featured']     = 'Nur hervorgehobene Beiträge anzeigen';
$GLOBALS['TL_LANG']['tl_module']['unfeatured']   = 'Hervorgehobene Beiträge überspringen';

$GLOBALS['tl_module']['news4ward_order_ref']	= array
(
	'start ASC'		=> 'Anzeigedatum aufsteigend',
	'start DESC'	=> 'Anzeigedatum absteigend',
	'title ASC'		=> 'Beitragstitel aufsteigend',
	'title DESC'	=> 'Beitragstitel absteigend'
);

?>