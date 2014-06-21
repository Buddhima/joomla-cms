<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Media component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_media
 * @since       3.5
 */
class MediaViewImageslistHtml extends ConfigViewCmsHtml
{
	public function render()
	{
		// Do not allow cache
		JFactory::getApplication()->allowCache(false);

		$lang	= JFactory::getLanguage();

		JHtml::_('stylesheet', 'media/popup-imagelist.css', array(), true);

		if ($lang->isRTL())
		{
			JHtml::_('stylesheet', 'media/popup-imagelist_rtl.css', array(), true);
		}

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("var ImageManager = window.parent.ImageManager;");

		$images = $this->model->getImages();
		$folders = $this->model->getFolders();
		$state = $this->model->getState();

		$this->baseURL = COM_MEDIA_BASEURL;
		$this->images = &$images;
		$this->folders = &$folders;
		$this->state = &$state;

		return parent::render();
	}

	function setFolder($index = 0)
	{
		if (isset($this->folders[$index]))
		{
			$this->_tmp_folder = &$this->folders[$index];
		}
		else
		{
			$this->_tmp_folder = new JObject;
		}
	}

	function setImage($index = 0)
	{
		if (isset($this->images[$index]))
		{
			$this->_tmp_img = &$this->images[$index];
		}
		else
		{
			$this->_tmp_img = new JObject;
		}
	}
}
