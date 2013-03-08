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



// GlobalContentelements switch
if($this->Input->get('do') == 'news4ward')
{
	$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_news4ward_article';
	
	// set news4wards checkPermissions function
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_news4ward', 'checkPermission');
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('\News4ward\Helper', 'setFiletreePath');
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
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->news4ward) || empty($this->User->news4ward))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->news4ward;
		}

		// Check the current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case '': // empty
			case 'create':
			case 'select':
				// Check access to the news item
				if (!$this->checkAccessToElement(CURRENT_ID, $root, true))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				// Check access to the parent element if a content element is moved
				if ((Input::get('act') == 'cutAll' || Input::get('act') == 'copyAll') && !$this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}

				$objCes = $this->Database->prepare("SELECT id FROM tl_content WHERE ptable='tl_news4ward_article' AND pid=?")
										 ->execute(CURRENT_ID);

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCes->fetchEach('id'));
				$this->Session->setData($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				if (!$this->checkAccessToElement(Input::get('pid'), $root, (Input::get('mode') == 2)))
				{
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			default:
				// Check access to the content element
				if (!$this->checkAccessToElement(Input::get('id'), $root))
				{
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Check access to a particular content element
	 * @param integer
	 * @param array
	 * @param boolean
	 * @return boolean
	 */
	protected function checkAccessToElement($id, $root, $blnIsPid=false)
	{
		if ($blnIsPid)
		{
			$objArchive = $this->Database->prepare("SELECT a.id, n.id AS nid, n.author FROM tl_news4ward_article n, tl_news4ward a WHERE n.id=? AND n.pid=a.id")
										 ->limit(1)
										 ->execute($id);
		}
		else
		{
			$objArchive = $this->Database->prepare("SELECT a.id, n.id AS nid, n.author FROM tl_content c, tl_news4ward_article n, tl_news4ward a WHERE c.id=? AND c.pid=n.id AND n.pid=a.id")
										 ->limit(1)
										 ->execute($id);
		}

		// Invalid ID
		if ($objArchive->numRows < 1)
		{
			$this->log('Invalid news4ward content element ID ' . $id, 'tl_content_news4ward checkAccessToElement()', TL_ERROR);
			return false;
		}

		// The news archive is not mounted
		if (!in_array($objArchive->id, $root))
		{
			$this->log('Not enough permissions to modify new4ward_article ID ' . $objArchive->nid . ' in news4ward archive ID ' . $objArchive->id, 'tl_content_news4ward checkAccessToElement()', TL_ERROR);
			return false;
		}

		// check if the user is only allowed to edit his own articles and this CE blongs to one of that
		if(is_array($this->User->news4ward_itemRights) && in_array('onlyOwn',$this->User->news4ward_itemRights))
		{
			if($objArchive->author != $this->User->id)
			{
				$this->log('Not enough permissions to modify content element of new4ward_article ID ' . $objArchive->nid . ' in news4ward archive ID ' . $objArchive->id, 'tl_content_news4ward checkAccessToElement()', TL_ERROR);
				return false;
			}
		}


		return true;
	}


}