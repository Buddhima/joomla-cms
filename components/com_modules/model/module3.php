<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Module model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 * @since       1.6
 */
class ModulesModelModule3 extends JModelCmsform
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_MODULES';

	/**
	 * @var    string  The help screen key for the module.
	 * @since  1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_MODULE_MANAGER_EDIT';

	/**
	 * @var    string  The help screen base URL for the module.
	 * @since  1.6
	 */
	protected $helpURL;
	
	public $currentModel;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{

		
		
		$app = JFactory::getApplication('administrator');
		
		// Load the User state.
		$pk = $app->input->getInt('id');
		
		$state = $this->loadState();
		
		// Load the parameters.
		$params	= JComponentHelper::getParams('com_modules');
		$state->set('params', $params);
		$state->set('module.id', $pk);
		
		$this->setState($state);
	}



	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{


		// Get the form.
		$form = $this->loadForm('com_modules.module', 'module', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			
			return false;
		}
		
		$form->setFieldAttribute('position', 'client',  'site');

		return $form;
	}


	/**
	 * Method to preprocess the form
	 *
	 * @param   JForm   $form   A form object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error loading the form.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		jimport('joomla.filesystem.path');

		$lang     = JFactory::getLanguage();
// 		$module   = $this->getState('item.module');

		$module = $this->currentModel;
		$adminPath = JPATH_ADMINISTRATOR;
		
		$formFile = JPath::clean($adminPath . '/modules/' . $module . '/' . $module . '.xml');

		// Load the core and/or local language file(s).
		$lang->load($module, $adminPath, null, false, false)
			||	$lang->load($module, $adminPath . '/modules/' . $module, null, false, false)
			||	$lang->load($module, $adminPath, $lang->getDefault(), false, false)
			||	$lang->load($module, $adminPath . '/modules/' . $module, $lang->getDefault(), false, false);

		if (file_exists($formFile))
		{
			// Get the module form.
			if (!$form->loadFile($formFile, false, '//config'))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}

			// Attempt to load the xml file.
			if (!$xml = simplexml_load_file($formFile))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}

		}

		// Load the default advanced params
		JForm::addFormPath(JPATH_BASE . '/components/com_modules/model/form');
		$form->loadFile('advanced', false);

		// Trigger the default form events.
		parent::preprocessForm($form, $data, $group);
	}


}
