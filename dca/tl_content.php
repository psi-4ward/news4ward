<?php

// GlobalContentelements switch
if($this->Input->get('do') == 'news4ward')
{
	$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_news4ward_article';
}

