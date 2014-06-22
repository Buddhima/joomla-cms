<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLog::add(
'MediaViewMediaList is deprecated. Use MediaViewMedialistHtml instead.',
JLog::WARNING,
'deprecated'
);

include_once JPATH_ADMINISTRATOR . '/components/com_media/view/medialist/html.php';
