<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_services
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Sessions
jimport('joomla.session.session');

// Load classes
JLoader::registerPrefix('Modules', JPATH_COMPONENT);

// Tell the browser not to cache this page.
JResponse::setHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true);

// Application
$app = JFactory::getApplication();

$controller = $app->input->get('controller');



// Get the controller name
if (empty($controller))
{
	$activity = 'display';
	
}
elseif ($controller == 'apply')
{
	$activity = 'save';
}
else
{ throw new dsfsd();
	$activity = $controller;
}



$classname  = 'ModulesController' . ucfirst($activity);
	
	
if(!class_exists($classname))
{
	$app->enqueueMessage(JText::_('COM_MODULES_ERROR_CONTROLLER_NOT_FOUND'), 'error');

	return;

}

$controller = new $classname;

// Perform the Request task
$controller->execute();
