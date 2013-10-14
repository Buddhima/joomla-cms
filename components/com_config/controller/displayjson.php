<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Display Controller for global configuration
 * 
 * Note: 1. Must access a backend component
 * 		 2. Must use JSON to get data from backend (backend must have a JSON view)
 * 		 3. Frontend view is only in HTML
 *
 * @package     Joomla.Site
 * @subpackage  com_config
 * @since       3.2
 */
class ConfigControllerDisplayjson extends ConfigControllerDisplay
{
	public $backendControllerView;//in com_config 2 views: application & component
	public $backendComponent;// for accessing differnt component than front-end - but new MVC
	public $backendController; // Just because of legacy diplay controllers

	/**
	 * Method to displayServices.
	 *
	 * @return  boolean	True on success, false on failure.
	 *
	 * @since   3.2
	 */
	public function execute()
	{

		// Get the application
		$app = $this->getApplication();

		// Get the document object.
		$document = JFactory::getDocument();

		$viewName = (!empty($this->options[2]) ? $this->options[2] : '');// assigned by ControllerHelper
		$layoutName   = $this->input->getWord('layout', 'default');

		// Access back-end
		if(!empty($this->backendComponent))
		{
			JLoader::registerPrefix(ucfirst($this->backendComponent), JPATH_ADMINISTRATOR . '/components/com_'.strtolower($this->backendComponent));

			$this->backendControllerString = strtolower($this->backendComponent).'.display';

		}
		else
		{
			JLoader::registerPrefix(ucfirst($this->prefix), JPATH_ADMINISTRATOR . '/components/com_'.strtolower($this->prefix));

			$this->backendControllerString = strtolower($this->prefix).'.display';
		}

		// Set back-end required params
		$document->setType('json');
		$app->input->set('view', $this->backendControllerView);

		if(!empty($this->backendControllerView))
		{
			$this->backendControllerString .= '.'.$this->backendControllerView;
		}

		$app->input->set('controller', $this->backendControllerString);

		if(!empty($this->backendController))
		{
			// Bypass for old MVC display
			$backendDisplayClass = new $this->backendController;
			// Execute back-end controller
			$serviceData = json_decode($backendDisplayClass->display(), true);
		}
		else
		{
			$helper = new ConfigControllerHelper();
			$backendDisplayClass = $helper->parseController($app);
			// Execute back-end controller
			$serviceData = json_decode($backendDisplayClass->execute(), true);
		}

		// Register the layout paths for the view
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/view/' . $viewName . '/tmpl', 'normal');

		$viewClass  = $this->prefix . 'View' . ucfirst($viewName) . 'Html';
		$modelClass = $this->prefix . 'Model' . ucfirst($viewName);

		if (class_exists($viewClass))
		{

			$model = new $modelClass;

			// Access check.
			if (!JFactory::getUser()->authorise('core.admin', $model->getState('component.option')))
			{
				$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

				return;
			}

			$view = new $viewClass($model, $paths);

			$view->setLayout($layoutName);

			// Push document object into the view.
			$document->setType('html');	// Reset params back after requesting from service
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
