<?php

/**
 * @copyright 4ward.media 2013 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */

// News4ward Articles
$GLOBALS['TL_DCA']['tl_stylepicker4ward']['fields']['_news4ward_Article'] = array
(
	'label'				      => &$GLOBALS['TL_LANG']['tl_stylepicker4ward']['_Article'],
	'inputType'               => 'checkbox',
	'load_callback'			  => array(array('tl_stylepicker4ward_news4ward','loadNews4wardArticles')),
	'save_callback'			  => array(array('tl_stylepicker4ward_news4ward','saveNews4wardArticles')),
	'eval'					  => array('doNotSaveEmpty'=>true, 'tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_stylepicker4ward']['fields']['_news4ward_ArticleTeaser'] = array
(
	'label'					  => &$GLOBALS['TL_LANG']['tl_stylepicker4ward']['_ArticleTeaser'],
	'inputType'				  => 'checkbox',
	'load_callback'			  => array(array('tl_stylepicker4ward_news4ward','loadNews4wardArticlesTeasers')),
	'save_callback'			  => array(array('tl_stylepicker4ward_news4ward','saveNews4wardTeasers')),
	'eval'					  => array('doNotSaveEmpty'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_stylepicker4ward']['palettes']['default'] .= ';{News4ward},_news4ward_Article, _news4ward_ArticleTeaser';

class tl_stylepicker4ward_news4ward extends \Stylepicker4ward\DcaHelper
{

	public function saveNews4wardArticles($val, $dc)
	{
		// delete all records for this table/pid
		$this->truncateTargets($dc->id, 'tl_news4ward_article', 'cssID');

		if(strlen($val))
		{
			$this->saveTarget($dc->id, 'tl_news4ward_article', 'cssID');
		}
		return '';
	}
	public function loadNews4wardArticles($val, $dc)
	{
		$objTargets = $this->Database->prepare('SELECT count(pid) AS anz FROM tl_stylepicker4ward_target WHERE pid=? AND tbl=? AND fld=?')->execute($dc->id, 'tl_news4ward_article', 'cssClass');
		return ($objTargets->anz > 0) ? '1' : '';
	}


	public function saveNews4wardTeasers($val, $dc)
	{
		// delete all records for this table/pid
		$this->truncateTargets($dc->id, 'tl_news4ward_article', 'teaserCssID');

		if(strlen($val))
		{
			$this->saveTarget($dc->id, 'tl_news4ward_article', 'teaserCssID');
		}
		return '';
	}
	public function loadNews4wardArticlesTeasers($val, $dc)
	{
		$objTargets = $this->Database->prepare('SELECT count(pid) AS anz FROM tl_stylepicker4ward_target WHERE pid=? AND tbl=? AND fld=?')->execute($dc->id, 'tl_news4ward_article', 'teaserCssID');
		return ($objTargets->anz > 0) ? '1' : '';
	}

}
