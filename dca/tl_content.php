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



// GlobalContentelements switch
if($this->Input->get('do') == 'news4ward')
{
	$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_news4ward_article';
}

