<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Save Controller for global configuration
 *
 * @package     Joomla.Site
 * @subpackage  com_services
 * @since       3.2
*/
class ModulesControllerSave extends JControllerBase
{
	/**
	 * Method to save global configuration.
	 *
	 * @return  bool	True on success.
	 *
	 * @since   3.2
	 */
	public function execute()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Check if the user is authorized to do this.
		if (!JFactory::getUser()->authorise('core.admin'))
		{
			JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));

			return;
		}

		// Set FTP credentials, if given.
		JClientHelper::setCredentialsFromRequest('ftp');

		$app   = JFactory::getApplication();
// 		$data  = $this->input->post->get('jform', array(), 'array');

		// Access back-end com_modules to be done
		JLoader::register('ModulesControllerModule', JPATH_ADMINISTRATOR . '/components/com_modules/controllers/module.php');
		JLoader::register('ModulesModelModule', JPATH_ADMINISTRATOR . '/components/com_modules/models/module.php');
// 		JLoader::register('ModulesTableModule', JPATH_ADMINISTRATOR . '/components/com_templates/tables/style.php');
		$controllerClass = new ModulesControllerModule;

		// Get a document object
		$document = JFactory::getDocument();

		// Set back-end required params
		$document->setType('json');
// 		$this->input->set('id',$app->input->getInt('id') );//** Set Id of module **//
		
// 		print_r($this->input->get('id'));
		

		// Execute back-end controller
		$return = $controllerClass->save();

		// Reset params back after requesting from service
		$document->setType('html');


		// Check the return value.
		if ($return === false)
		{
			// Save the data in the session.
// 			$app->setUserState('com_modules.config.global.data', $data);

			// Save failed, go back to the screen and display a notice.
			$message = JText::sprintf('JERROR_SAVE_FAILED');

			$app->redirect(JRoute::_('index.php?option=com_modules&controller=display', false), $message, 'error');

			return false;
		}

		// Set the success message.
		$message = JText::_('COM_MODULES_SAVE_SUCCESS');

		// Redirect back to com_services display
		$app->redirect(JRoute::_('index.php?option=com_modules&controller=display', false), $message);

		return true;
	}
}