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
			array('tl_news4ward_article', 'generateFeed'),
			array('News4wardHelper', 'setFiletreePath'),
		),
		'onsubmit_callback' 		  => array(array('tl_news4ward_article', 'scheduleUpdate')),
		'ondelete_callback'			  => array(array('GlobalContentelements', 'deleteChildRecords'))
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('start DESC'),
			'panelLayout'             => 'filter,limit;search,sort',
			'headerFields'            => array('title','protected'),
			'child_record_callback'   => array('tl_news4ward_article', 'listItem')
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
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_news4ward_article']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_news4ward_article', 'editHeader'),
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
		'__selector__'				  => array('useFacebookImage'),
		'default'                     => '{title_legend},title,alias,author,highlight,sticky;{layout_legend},description,keywords;{teaser_legend:hide},subheadline,teaserCssID,teaser,teaserImage,teaserImageCaption;{facebook_legend},useFacebookImage;{expert_legend:hide},social,cssID;{publish_legend},start,stop,status'
	),

	'subpalettes' => array
	(
		'useFacebookImage'			  => 'facebookImage'
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

		'subheadline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['subheadline'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long')
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
		'teaserImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImage'],
			'inputType'               => 'fileTree',
			'exclude'                 => true,
			'eval'                    => array('fieldType'=>'radio', 'files'=>'true', 'filesOnly'=>true, 'extensions'=>'jpg,gif,png')
		),
		'teaserImageCaption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['teaserImageCaption'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long')
		),
		'social' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['social'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'default'				  => serialize(array('facebook', 'twitter','google','email')),
			'options'                 => array('facebook', 'twitter','google','email'),
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
			'eval'                    => array('mandatory'=>true,'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'sticky' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'eval'                    => array('tl_class'=>'w50')
		),
		'useFacebookImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['useFacebookImage'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'eval'                    => array('submitOnChange'=>'true')
		),
		'facebookImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_news4ward_article']['facebookImage'],
			'inputType'               => 'fileTree',
			'exclude'                 => true,
			'eval'                    => array('fieldType'=>'radio', 'files'=>'true', 'filesOnly'=>true, 'extensions'=>'jpg,gif,png')
		),
	)
);


class tl_news4ward_article extends Backend
{

	protected static $authorCache = array();

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import('Database');
	}


	/**
	 * Generate listItem
	 * @param array
	 * @return string
	 */
	public function listItem($arrRow)
	{
		// the title
		$strReturn .= ' <div style="font-weight:bold;margin-bottom:5px;line-height:18px;height:18px;">'.$this->generateImage('articles.gif','','style="vertical-align:bottom;"').' '.$arrRow['title'].'</div>';

		// show the autor
		if(!empty($arrRow['author']))
		{
			if(!isset(self::$authorCache[$arrRow['author']]))
			{
				$objAuthor = $this->Database->prepare('SELECT name FROM tl_user WHERE id=?')->execute($arrRow['author']);
				if($objAuthor->numRows)
				{
					self::$authorCache[$arrRow['author']] = $objAuthor->name;
				}
				else
				{
					self::$authorCache[$arrRow['author']] = false;
				}
			}
			if(self::$authorCache[$arrRow['author']])
			{
				$strReturn .= '<div style="color:#999;margin-bottom:5px;">'.$GLOBALS['TL_LANG']['tl_news4ward_article']['author'][0].': '.self::$authorCache[$arrRow['author']].'</div>';
			}
		}

		// generate the status icons
		$strReturn .= '<div style="margin-bottom:5px;">'.$GLOBALS['TL_LANG']['tl_news4ward_article']['status'][0].': ';
		$strReturn .= '<a href="#" onclick="javascript:News4ward.showStatusToggler(this,\''.$arrRow['id'].'\'); return false;">';
		if($arrRow['status'] == 'draft')
		{
			$strReturn .= $this->generateImage(	'system/modules/news4ward/html/draft.png',
												$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']],
												'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']].'"');
		}
		else if($arrRow['status'] == 'review')
		{
			$strReturn .= $this->generateImage('system/modules/news4ward/html/review.png',
												$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']],
												'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$arrRow['status']].'"');
		}
		else
		{
			$published = ($arrRow['status'] == 'published' && ($arrRow['start'] == '' || $arrRow['start'] < time()) && ($arrRow['stop'] == '' || $arrRow['stop'] > time()));
			$strReturn .= $this->generateImage('system/modules/news4ward/html/'.($published ? '' : 'not').'published.png','','');
		}
		$strReturn .= '</a>';

		// generate the status toggler popup
		$strReturn .= '<div class="news4wardStatusToggler">';
		foreach($GLOBALS['TL_DCA']['tl_news4ward_article']['fields']['status']['options'] as $status)
		{
			$strReturn .= '<a href="#" onclick="News4ward.setStatus(this,\''.$arrRow['id'].'\',\''.$status.'\'); return false;">';
			$strReturn .= $this->generateImage(	'system/modules/news4ward/html/'.$status.'.png',
												$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$status],
												'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$status].'"');
			$strReturn .= ' '.$GLOBALS['TL_LANG']['tl_news4ward_article']['stati'][$status];
			$strReturn .= '</a>';
		}

		$strReturn .= '</div>';

		if($arrRow['highlight'])
		{
			$strReturn .= ' '.$this->generateImage('system/modules/news4ward/html/highlight.png',$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight'][0],'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['highlight'][0].'"');
		}
		if($arrRow['sticky'])
		{
			$strReturn .= ' '.$this->generateImage('system/modules/news4ward/html/sticky.png',$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'][0],'title="'.$GLOBALS['TL_LANG']['tl_news4ward_article']['sticky'][0].'"');
		}
		$strReturn .= '</div>';

		// generate start / end date
		$strReturn .= '<div style="color:#999;">';
		$strReturn .= $GLOBALS['TL_LANG']['tl_news4ward_article']['start'][0].': '.$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'],$arrRow['start']);
		if(!empty($arrRow['stop'])) $strReturn .= ' <br> '	.$GLOBALS['TL_LANG']['tl_news4ward_article']['stop'][0].': '.$this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'],$arrRow['stop']);
		$strReturn .= '</div>';

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['news4ward_article_generateListItem']) && is_array($GLOBALS['TL_HOOKS']['news4ward_article_generateListItem']))
		{
			foreach ($GLOBALS['TL_HOOKS']['news4ward_article_generateListItem'] as $callback)
			{
				$this->import($callback[0]);
				$strReturn = $this->$callback[0]->$callback[1]($strReturn,$arrRow);
			}
		}

		return $strReturn;
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
	 * Check permissions to edit table tl_news4ward_article
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			// allow admins
			return;
		}

		// find tl_news4archiv.id
		if(!$this->Input->get('act') || in_array($this->Input->get('act'),array('create','select','editAll','overrideAll')))
		{
			$news4wardID = $this->Input->get('id');
		}
		else
		{
			$objArticle = $this->Database->prepare('SELECT pid FROM tl_news4ward_article WHERE id=?')->execute($this->Input->get('id'));
			$news4wardID = $objArticle->pid;
		}

		if(is_array($this->User->news4ward) && count($this->User->news4ward) > 0 && in_array($news4wardID,$this->User->news4ward)) return;

		$this->log('Not enough permissions to '.$this->Input->get('act').' news4ward archive ID "'.$news4wardID.'"', 'tl_news4ward checkPermission', TL_ERROR);
		$this->redirect('contao/main.php?act=error');
	}


/**
	 * Check for modified news feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('news4ward_feed_updater');

		if (!is_array($session) || count($session) < 1)
		{
			return;
		}

		$this->import('News4wardHelper');

		foreach ($session as $id)
		{
			$this->News4wardHelper->generateFeed($id);
		}

		$this->Session->set('news4ward_feed_updater', NULL);
	}


	/**
	 * Schedule a news feed update
	 *
	 * This method is triggered when a single news archive or multiple news
	 * archives are modified (edit/editAll).
	 *
	 * @param \DataContainer $dc
	 * @return void
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no PID
		if (!$dc->activeRecord->pid)
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('news4ward_feed_updater');
		$session[] = $dc->activeRecord->pid;
		$this->Session->set('news4ward_feed_updater', array_unique($session));
	}
}

?>