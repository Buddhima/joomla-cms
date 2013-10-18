<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Display Controller for global configuration
 *
 * @package     Joomla.Site
 * @subpackage  com_config
 * @since       3.2
*/
class ConfigControllerTemplatesDisplay extends ConfigControllerDisplayjson
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

		// Access back-end com_config
		JLoader::register('TemplatesController', JPATH_ADMINISTRATOR . '/components/com_templates/controller.php');
		JLoader::register('TemplatesViewStyle', JPATH_ADMINISTRATOR . '/components/com_templates/views/style/view.json.php');
		JLoader::register('TemplatesModelStyle', JPATH_ADMINISTRATOR . '/components/com_templates/models/style.php');

		$this->input->set('id', $this->app->getTemplate('template')->id);

		$this->backendComponent = 'templates'; // Actually no need since bypassing
		$this->backendController = 'TemplatesController';

		// Access check.
		if (!JFactory::getUser()->authorise('core.manage', 'com_config') || !JFactory::getUser()->authorise('core.edit', 'com_config'))
		{
			$this->app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

			return;
		}
		else
		{
			return parent::execute();
		}

	}

}
