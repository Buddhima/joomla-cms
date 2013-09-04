<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php
// 	echo JHtml::_('bootstrap.startAccordion', 'moduleOptions', array('active' => 'collapse0'));
	$fieldSets = $this->form->getFieldsets('params');
	$i = 0;

// 	print_r($fieldSets); throw new ex();
	
	
	
	foreach ($fieldSets as $name => $fieldSet) :
		$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_MODULES_'.$name.'_FIELDSET_LABEL';
		$class = isset($fieldSet->class) && !empty($fieldSet->class) ? $fieldSet->class : '';

// 		echo JHtml::_('bootstrap.addSlide', 'moduleOptions', JText::_($label), 'collapse' . $i++, $class);
			if (isset($fieldSet->description) && trim($fieldSet->description)) :
				echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
			endif;
			?>
				<?php 
// 				print_r($this->form); //throw new errr();
// 				$this->form->removeField('params');
// 				print_r('@#$#$@#$@#$@#$@');
// 				print_r($this->form);
// 				print_r($this->form->getFieldset('advanced'));throw new ee();

// 				print_r($this->form);
				echo('<br/><br/>');
				
				foreach ($this->form->getFieldset($name) as $field) : 
				
//				print_r($field->input);

// 				if($field->label=="COM_MODULES_FIELD_MODULE_STYLE_LABEL"){ continue;  print_r('REWRWRWER');}
				
				
				?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach;
// 		echo JHtml::_('bootstrap.endSlide');
	endforeach;
// echo JHtml::_('bootstrap.endAccordion');
