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
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'] = array(array('tl_content_news4ward', 'checkPermission'));
}

class tl_content_news4ward extends Backend
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
	public function checkPermission($dc)
	{

		if ($this->User->isAdmin)
		{
			return;
		}

		
		if($dc->table == 'tl_content')
			$id = $this->Database->prepare('SELECT pid FROM tl_content WHERE id=?')->execute($dc->id)->pid;
		else
			$id = $dc->id; 
		
		// get archive id
		$objArchive = $this->Database->prepare('SELECT pid FROM tl_news4ward_article WHERE id=?')->execute($id);
		if($objArchive->numRows < 1 || !is_array($this->User->news4ward) || !in_array($objArchive->pid,$this->User->news4ward))
		{
			$this->log('Not enough permissions to '.$this->Input->get('act').' news4ward contentelement ID "'.$this->Input->get('id').'"', 'tl_content_news4ward checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		
	}
}