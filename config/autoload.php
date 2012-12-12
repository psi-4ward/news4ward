<?php

\Contao\ClassLoader::addNamespace('Psi');

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Psi\News4ward\Module\Listing'   	=> 'system/modules/news4ward/Module/Listing.php',
	'Psi\News4ward\Module\Module'		=> 'system/modules/news4ward/Module/Module.php',
	'Psi\News4ward\Module\Reader' 		=> 'system/modules/news4ward/Module/Reader.php',
	'Psi\News4ward\Helper'       		=> 'system/modules/news4ward/Helper.php'
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_news4ward_list'    => 'system/modules/news4ward/templates',
	'mod_news4ward_reader'  => 'system/modules/news4ward/templates',
	'news4ward_filter_hint' => 'system/modules/news4ward/templates',
	'news4ward_list_item'   => 'system/modules/news4ward/templates',
));
