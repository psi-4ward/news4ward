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
$GLOBALS['TL_LANG']['tl_module']['news4ward_perPage']       = array('Beiträge pro Seite', 'Die Anzahl der Beiträge pro Seite. 0 bedeutet keine Seitenaufteilung.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_skipFirst']     = array('Beiträge auslassen', 'Hier kann die Anzahl der auszulassenden Beiträge festgelegt werden.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_numberOfItems'] = array('Gesamtzahl der Beiträge', 'Hier können Sie die Gesamtzahl der Beiträge festlegen. Geben Sie 0 ein, um alle anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_jumpToCurrent'] = array('Kein Zeitraum ausgewählt', 'Hier legen Sie fest, was angezeigt wird, wenn kein Zeitraum ausgewählt ist.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_metaFields']    = array('Meta-Felder', 'Hier können Sie die Meta-Felder auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_template']      = array('Beitragtemplate', 'Hier können Sie das Beitragtemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_readerTemplate'] = array('Leser-Template', 'Hier können Sie das Leser-Template auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_format']        = array('Archivformat', 'Hier können Sie das Archivformat auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_startDay']      = array('Erster Wochentag', 'Hier können Sie den ersten Tag der Woche festlegen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_order']         = array('Sortierreihenfolge', 'Hier können Sie die Sortierreihenfolge festlegen.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_showQuantity']  = array('Anzahl anzeigen', 'Zeigt die Anzahl der Beiträge in Bezug auf den anzuwendenden Filter an.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_facebookMeta']  = array('Facebook Metadaten erzeugen', 'Die Facebook Metadaten sind Attribute, die beim Sharen oder Teilen verwendet werden. Unter anderem wird das Teaserbild für Facebook verwendet.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_filterHint']  	= array('Filter Hinweis', 'Ist der Filter dieses Modul gesetzt kann dieser über den Insert-Tag {{news4ward::filter_hint}} nochmals ausgegeben werden. Der Wert dieses Feldes beschreibt die Filtereigenschaft.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint']  	= array('Zeitbeschränkung', 'Hier können Sie die anzuzeigenden Beiträge auf ein Zeitintervall beschränken.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_overwriteArchiveJumpTo']  	= array('Weiterleitungsseite des Archivs überschreiben', 'Wählen Sie eine individuelle Leser-Seite für diese Liste.');
$GLOBALS['TL_LANG']['tl_module']['news4ward_ignoreFilters']  	= array('Filter ignorieren', 'Gesetzte Filter werden von dieser Beitragsliste nicht beachtet.');


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

$GLOBALS['TL_LANG']['tl_module']['news4ward_order_ref']	= array
(
	'start ASC'		=> 'Anzeigedatum aufsteigend',
	'start DESC'	=> 'Anzeigedatum absteigend',
	'title ASC'		=> 'Beitragstitel aufsteigend',
	'title DESC'	=> 'Beitragstitel absteigend',
    'random'        => 'zufällig'
);

$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['all'] = 'Alle Beiträge';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['cur_month'] = 'des laufenden Monats';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['cur_year'] = 'des laufenden Jahres';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_7'] = '- 1 Woche';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_14'] = '- 2 Wochen';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_30'] = '- 1 Monat';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_90'] = '- 3 Monate';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_180'] = '- 6 Monate';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_365'] = '- 1 Jahr';
$GLOBALS['TL_LANG']['tl_module']['news4ward_timeConstraint_ref']['past_two'] = '- 2 Jahre';
