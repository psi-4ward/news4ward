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
	
	// set news4wards checkPermissions function
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_news4ward', 'checkPermission');
	$GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['button_callback'] = array('tl_content_news4ward', 'toggleIcon');
}

class tl_content_news4ward extends tl_content
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Check permissions to edit table tl_content
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			// allow admins
			return;
		}


		// find tl_news4archiv.id
		if(!$this->Input->get('act') || in_array($this->Input->get('act'),array('create','select','editAll','overrideAll')) || $this->Input->get('act') == 'paste' && $this->Input->get('mode') == 'create')
		{
			$objArticle = $this->Database->prepare('SELECT pid FROM tl_news4ward_article WHERE id=?')->execute($this->Input->get('id'));
			$news4wardID = $objArticle->pid;
		}
		else
		{
			$objCE = $this->Database->prepare('SELECT a.pid FROM tl_content AS c LEFT JOIN tl_news4ward_article AS a ON (c.pid=a.id) WHERE c.id=?')->execute($this->Input->get('id'));
			$news4wardID = $objCE->pid;
		}



		if(is_array($this->User->news4ward) && count($this->User->news4ward) > 0 && in_array($news4wardID,$this->User->news4ward)) return;
		$this->log('Not enough permissions to manage tl_content '.$this->Input->get('act').' with news4ward ID "'.$news4wardID.'"', 'tl_content_news4ward checkPermission', TL_ERROR);
		$this->redirect('contao/main.php?act=error');
	}


}