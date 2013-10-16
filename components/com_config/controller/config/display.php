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
 * @package     Joomla.Site
 * @subpackage  com_config
 * @since       3.2
 */
class ConfigControllerConfigDisplay extends ConfigControllerDisplayjson
{
	/**
	 * Method to display global configuration.
	 *
	 * @return  boolean	True on success, false on failure.
	 *
	 * @since   3.2
	 */
	public function execute()
	{

		// 		$this->backendComponent = 'config'; // No need since backend with same component name
		$this->backendControllerView = 'application';// view name of the component (sub-section)

		// Access check.
		if (!JFactory::getUser()->authorise('core.admin', 'com_config'))
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
