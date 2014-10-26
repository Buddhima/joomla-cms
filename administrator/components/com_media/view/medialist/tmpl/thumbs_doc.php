<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$user = JFactory::getUser();
$params = new JRegistry;
$dispatcher	= JEventDispatcher::getInstance();
$dispatcher->trigger('onContentBeforeDisplay', array('com_media.file', &$this->_tmp_doc, &$params));
?>
		<li class="span2">
			<input class="pull-left" type="checkbox" name="rm[]" value="<?php echo $this->_tmp_doc->name; ?>" />
			<?php if ($user->authorise('core.delete', 'com_media')):?>
				<a class="close delete-item" target="_top" href="index.php?option=com_media&amp;controller=media.delete.media&amp;tmpl=index&amp;<?php echo JSession::getFormToken(); ?>=1&amp;folder=<?php echo $this->state->get('folder'); ?>&amp;rm[]=<?php echo $this->_tmp_doc->name; ?>" rel="<?php echo $this->_tmp_doc->name; ?>" title="<?php echo JText::_('JACTION_DELETE');?>">
					<span class="label label-important">&#215;</span>
				</a>
			<?php endif;?>
			<article class="thumbnail center" >
				<div class="height-20">
					
				</div>
				<div class="height-60">
					<a style="display: block; width: 100%; height: 100%" title="<?php echo $this->_tmp_doc->name; ?>" >
						<?php echo JHtml::_('image', $this->_tmp_doc->icon_32, $this->_tmp_doc->name, null, true, true) ? JHtml::_('image', $this->_tmp_doc->icon_32, $this->_tmp_doc->title, null, true) : JHtml::_('image', 'media/con_info.png', $this->_tmp_doc->name, null, true); ?></a>
				</div>
				<div class="height-20" title="<?php echo $this->_tmp_doc->name; ?>" >
					
					<?php echo JHtml::_('string.truncate', $this->_tmp_doc->name, 8, false); ?>
					<div class="clearfix"></div>
				</div>
			</article>
		</li>
<?php
$dispatcher->trigger('onContentAfterDisplay', array('com_media.file', &$this->_tmp_doc, &$params));
