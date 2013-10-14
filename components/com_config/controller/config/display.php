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
class ConfigControllerConfigDisplay extends ConfigControllerDisplayservice
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
		$this->backendControllerView = 'application';

		return parent::execute();

	}

}
