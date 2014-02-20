<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Media Component Manager Editor Model
 *
 * @package     Joomla.Administrator
 * @subpackage  com_media
 * @since       3.3
 */
class MediaModelEditor extends ConfigModelForm
{
	public function getState($property = null, $default = null)
	{
		static $set;

		if (!$set)
		{
			$input = JFactory::getApplication()->input;

			$folder = $input->get('folder', '', 'path');
			$this->state->set('folder', $folder);

			$fieldid = $input->get('fieldid', '');
			$this->state->set('field.id', $fieldid);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->state->set('parent', $parent);
			$set = true;
		}

		if(!$property)
		{

			return parent::getState();
		}
		else
		{

			return parent::getState()->get($property, $default);
		}

	}

	public function getForm($data = array(), $loadData = true)
	{
		return;
	}

	public function getImage()
	{
		$app      = JFactory::getApplication();
		$fileName = $app->input->get('file');
		$folder   = $app->input->get('folder', '', 'path');
		$path     = JPath::clean(COM_MEDIA_BASE . '/');
		$uri      = COM_MEDIA_BASEURL . '/';

		if(!empty($folder))
		{
			$path     = JPath::clean(COM_MEDIA_BASE . '/' . $folder . '/');
			$uri      = COM_MEDIA_BASEURL . '/' . $folder .'/';
		}

		if (file_exists(JPath::clean($path . $fileName)))
		{
			$JImage = new JImage(JPath::clean($path . $fileName));
			$image['address'] 	= $uri . $fileName;
			$image['path']		= $fileName;
			$image['height'] 	= $JImage->getHeight();
			$image['width']  	= $JImage->getWidth();
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_MEDIA_ERROR_IMAGE_FILE_NOT_FOUND'), 'error');

			return false;
		}

		return $image;

	}

	/**
	 * Crop an image.
	 *
	 * @param   string  $file  The name and location of the file
	 * @param   string  $w     width.
	 * @param   string  $h     height.
	 * @param   string  $x     x-coordinate.
	 * @param   string  $y     y-coordinate.
	 *
	 * @return  boolean     true if image cropped successfully, false otherwise.
	 *
	 * @since   3.3
	 */
	public function cropImage($file, $w, $h, $x, $y)
	{
		$app      = JFactory::getApplication();

		$JImage   = new JImage($file);

		try
		{
			$image = $JImage->crop($w, $h, $x, $y, true);
			$image->toFile($file);

			return true;
		}
		catch (Exception $e)
		{
			$app->enqueueMessage($e->getMessage(), 'error');
		}

	}
}
