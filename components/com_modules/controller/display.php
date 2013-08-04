<?php 
/**
 * @package     Joomla.Site
 * @subpackage  com_services
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 <?php 
/**
 * @package     Joomla.Site
 * @subpackage  com_services
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Display Controller for global configuration
 *
 * @package     Joomla.Site
 * @subpackage  com_services
 * @since       3.2
*/
class ModulesControllerDisplay extends JControllerBase
{
	/**
	 * Method to display global configuration.
	 *
	 * @return  bool	True on success, false on failure.
	 * 
	 * @since   3.2
	 */
	public function execute()
	{

		// Get the application
		$app = $this->getApplication();

		// Get the document object.
		$document     = JFactory::getDocument();

		$viewName     = $app->input->getWord('view', 'templates');
		$viewFormat   = $document->getType();
		$layoutName   = $app->input->getWord('layout', 'default');

		$app->input->set('view', $viewName);

		// Access back-end com_module
		JLoader::register('ModulesController', JPATH_ADMINISTRATOR . '/components/com_modules/controller.php');
		//JLoader::register('TemplatesViewStyle', JPATH_ADMINISTRATOR . '/components/com_templates/views/style/view.json.php');
		JLoader::register('ModulesModelStyle', JPATH_ADMINISTRATOR . '/components/com_modules/models/ModulesModelModule.php');

		$displayClass = new ModulesController;

		// Get the parameters of the module with Id =1
		$document->setType('json');
		$app->input->set('id', $app->getTemplate(1));

		// Execute back-end controller
		$serviceData = json_decode($displayClass->display(), true);


		// Reset params back after requesting from service
		$document->setType('html');
		$app->input->set('view', $viewName);


		// Register the layout paths for the view
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/view/tmpl', 'normal');
		

		$viewClass  = 'ModulesView' . ucfirst($viewFormat);
		$modelClass = 'ModulesModel' . ucfirst($viewName);

		if (class_exists($viewClass))
		{

			if ($viewName != 'close')
			{
				$model = new $modelClass;

				// Access check.
 				if (!JFactory::getUser()->authorise('core.admin', $model->getState('component.option')))
				{
					$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

					return;

				}

			}

			$view = new $viewClass($model, $paths);

			$view->setLayout($layoutName);

			// Push document object into the view.
			$view->document = $document;

			// Load form and bind data
			$form = $model->getForm();
			if ($form)
			{
				$form->bind($serviceData);
			}

			// Set form and data to the view			
			$view->form = &$form;

			// Render view.
			echo $view->render();
		}
		return true;
	}

}