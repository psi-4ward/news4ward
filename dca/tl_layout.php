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


// Fields
$GLOBALS['TL_DCA']['tl_layout']['fields']['news4ward_feeds'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['news4ward_feeds'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_layout_news4ward', 'getNews4wardfeeds'),
	'eval'                    => array('multiple'=>true)
);

// Palette
$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] = str_replace('{feed_legend:hide}','{feed_legend:hide},news4ward_feeds',$GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);


class tl_layout_news4ward extends System
{

	/**
	 * Return all news4ward archives with XML feeds
	 * @return array
	 */
	public function getNews4wardfeeds()
	{
		$this->import('Database');
		$objFeed = $this->Database->execute("SELECT id, title FROM tl_news4ward WHERE makeFeed=1");

		if ($objFeed->numRows < 1)
		{
			return array();
		}

		$return = array();

		while ($objFeed->next())
		{
			$return[$objFeed->id] = $objFeed->title;
		}

		return $return;
	}
}
