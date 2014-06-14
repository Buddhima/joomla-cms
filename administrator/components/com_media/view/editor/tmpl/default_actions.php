<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tabstate');

?>

<p class="well well-small lead">

	<button type="submit" form="adminForm" class='btn btn-large'>
		<i class='icon-move' title='COM_MEDIA_EDITOR_BUTTON_CROP'></i>
		<?php echo JText::_('COM_MEDIA_EDITOR_BUTTON_CROP') ?>
	</button>
	
<br/>

</p>
