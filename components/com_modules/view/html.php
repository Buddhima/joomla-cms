<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a template style.
 *
 * @package     Joomla.Site
 * @subpackage  com_templates
 * @since       1.6
 */
class ModulesViewHtml extends JViewCms
{
	public $item;

	public $form;

	public $state;
	
	public $currentModelId;

	/**
	 * Display the view
	 */
	public function render()
	{
// 		$this->item		= $this->get('Item');
// 		$this->state	= $this->get('State');
// 		$this->form		= $this->model->getForm();


		// Check for errors.
		/* if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		} */

		$user = JFactory::getUser();
		$this->userIsSuperAdmin = $user->authorise('core.admin');

		//$this->addToolbar();
		return parent::render();
	}


}
