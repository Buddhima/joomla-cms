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
?>
		<li class="span2">
			<input class="pull-left" type="checkbox" name="rm[]" value="<?php echo $this->_tmp_folder->name; ?>" />
			<?php if ($user->authorise('core.delete', 'com_media')):?>
				<a class="close delete-item" target="_top" href="index.php?option=com_media&amp;controller=media.delete.medialist&amp;tmpl=index&amp;<?php echo JSession::getFormToken(); ?>=1&amp;folder=<?php echo $this->state->get('folder'); ?>&amp;rm[]=<?php echo $this->_tmp_folder->name; ?>" rel="<?php echo $this->_tmp_folder->name; ?> :: <?php echo $this->_tmp_folder->files + $this->_tmp_folder->folders; ?>" title="<?php echo JText::_('JACTION_DELETE');?>">
					<i class="icon-delete" style="font-size: small; color: #CB0B0B;"></i>
				</a>
			<?php endif;?>
			<article class="thumbnail center" >
				<div class="height-20">
				
				</div>
				<div class="height-60">
					<a href="index.php?option=com_media&amp;controller=media.display.medialist&amp;view=medialist&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_relative; ?>" target="folderframe">
						<i class="icon-folder-2" style="font-size: 40px;display: initial;"></i>
					</a>
				</div>
				<div class="height-20">
					<a href="index.php?option=com_media&amp;controller=media.display.medialist&amp;view=medialist&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_relative; ?>" target="folderframe"><?php echo JHtml::_('string.truncate', $this->_tmp_folder->name, 10, false); ?></a>
					
				</div>
			</article>
		</li>
