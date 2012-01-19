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




// Load class tl_page
$this->loadDataContainer('tl_page');


$GLOBALS['TL_DCA']['tl_news4ward_article'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_news4ward',
		'ctable'                      => array('tl_content'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_news4ward_article', 'checkPermission'),
	//		array('tl_page', 'addBreadcrumb')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('start DESC'),
			'panelLayout'             => 'filter,limit;search,sort'
		),
		'label' => array
		(
			'fields'                  => array('title', 'inColumn','start'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback'          => array('tl_news4ward_article', 'addIcon')
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
				'href'                => '&amp;ptg=all',
				'class'               => 'header_toggle'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['edit'],
				'href'                => 'table=tl_content',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_news4ward_article', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title,alias,category,author,highlight,sticky;{layout_legend},description,keywords;{teaser_legend:hide},teaserCssID,teaser;{expert_legend:hide},social,cssID,noComments;{publish_legend},start,stop,status'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['title'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['alias'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_news4ward_article', 'generateAlias')
			)

		),
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['category'],
			'inputType'               => 'select',
			'exclude'                 => true,
			'options_callback'        => array('tl_news4ward_article','getCategories'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['author'],
			'inputType'               => 'select',
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'foreignKey'              => 'tl_user.name',
			'filter'                  => 'true',
			'eval'                    => array('doNotCopy'=>true, 'mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'keywords' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['keywords'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('style'=>'height:60px;')
		),
        'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['description'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('style'=>'height:60px;')
		),
		'highlight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight'],
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'exclude'                 => true,
			'eval'                    => array('tl_class'=>'w50')
		),

		'teaserCssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserCssID'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaser'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr')
		),
		'social' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['social'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'options'                 => array('facebook', 'twitter'),
			'eval'                    => array('multiple'=>true,'tl_class'=>''),
			'reference'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
		'status' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['status'],
			'inputType'               => 'select',
			'exclude'                 => true,
			'filter'                  => true,
			'options'                 => array('published','review','draft'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'],
			'eval'                    => array('doNotCopy'=>true,'tl_class'=>'w50')
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['start'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'default'				  => time(),
			'sorting'				  => true,
			'flag'					  => 8,
			'eval'                    => array('mandatory'=>true,'rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'noComments' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['noComments'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'eval'                    => array('tl_class'=>'w50')
		),
		'sticky' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'eval'                    => array('tl_class'=>'w50')
		)
	)
);


class tl_news4ward_article extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('Database');
	}


	public function getCategories($dc)
	{
		$arrCategories = array();
		$categories = $this->Database->prepare('SELECT categories FROM tl_news4ward WHERE id=?')->execute($dc->activeRecord->pid);
		$categories = deserialize($categories->categories,true);
		foreach($categories as $v)
		{
			$arrCategories[] = $v['category'];
		}
		return $arrCategories;
	}




	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		if($row['status'] == 'draft')
			return $this->generateImage('system/modules/news4ward/html/draft.gif') .' '. $label;
		else if($row['status'] == 'review')
			return $this->generateImage('system/modules/news4ward/html/review.gif') .' '. $label;


		$time = time();
		$published = ($row['status'] == 'published' && ($row['start'] == '' || $row['start'] < $time) && ($row['stop'] == '' || $row['stop'] > $time));

		return $this->generateImage('articles'.($published ? '' : '_').'.gif') .' '. $label;
	}


	/**
	 * Auto-generate an article alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_news4ward_article WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}





	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->isAdmin && count(preg_grep('/^tl_news4ward_article::/', $this->User->alexf)) < 1)
		{
			return '';
		}

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return the copy article button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyArticle($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return ($this->User->isAdmin || $this->User->isAllowed(5, $objPage->row())) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Check permissions to edit table tl_news4ward_article
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			// allow admins
			return;
		}

		if($this->Input->get('act'))
		{
			// get archive ID
			 $objArchive = $this->Database->prepare('SELECT pid FROM tl_news4ward_article WHERE id=?')->execute($this->Input->get('id'));
			// allow actions
			if(is_array($this->User->news4ward) && count($this->User->news4ward) > 1 && $objArchive->numRows > 0 && in_array($objArchive->pid,$this->User->news4ward)) return;
		}
		else
		{
			// allow listing
			if(is_array($this->User->news4ward) && count($this->User->news4ward) > 1 && in_array($this->Input->get('id'),$this->User->news4ward)) return;
		}


		$this->log('Not enough permissions to '.$this->Input->get('act').' news4ward archive ID "'.$this->Input->get('id').'"', 'tl_news4ward checkPermission', TL_ERROR);
		$this->redirect('contao/main.php?act=error');

	}

}

?>