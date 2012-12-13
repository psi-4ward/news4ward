<?php

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
 * Extend default palette
 */
$GLOBALS['TL_DCA']['tl_user_group']['palettes']['default'] = str_replace('fop;', 'fop;{news4ward_legend},news4ward,news4ward_newp,news4ward_itemRights;', $GLOBALS['TL_DCA']['tl_user_group']['palettes']['default']);


/**
 * Add fields to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user_group']['fields']['news4ward'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['news4ward'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_news4ward.title',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['news4ward_newp'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['news4ward_newp'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);
$GLOBALS['TL_DCA']['tl_user_group']['fields']['news4ward_itemRights'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['news4ward_itemRights'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('onlyOwn'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_user_group']['news4ward_itemRights_reference'],
	'eval'                    => array('multiple'=>true)
);

