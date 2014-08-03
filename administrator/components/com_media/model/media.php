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
 * Media Component Manager Model
 *
 * @package     Joomla.Administrator
 * @subpackage  com_media
 * @since       3.5
 */
class MediaModelMedia extends ConfigModelForm
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

			$parent = str_replace(DIRECTORY_SEPARATOR, "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->state->set('parent', $parent);
			$set = true;
		}

		if (!$property)
		{

			return parent::getState();
		}
		else
		{

			return parent::getState()->get($property, $default);
		}

	}

	/**
	 * Image Manager Popup
	 *
	 * @param   string  $base  The image directory to display
	 *
	 * @return  JHtml  Object that contains folder list to display
	 *
	 * @since 3.5
	 */
	public function getFolderList($base = null)
	{
		// Get some paths from the request
		if (empty($base))
		{
			$base = COM_MEDIA_BASE;
		}
		// Corrections for windows paths
		$base = str_replace(DIRECTORY_SEPARATOR, '/', $base);
		$com_media_base_uri = str_replace(DIRECTORY_SEPARATOR, '/', COM_MEDIA_BASE);

		// Get the list of folders
		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($base, '.', true, true);

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_MEDIA_INSERT_IMAGE'));

		// Build the array of select options for the folder list
		$options[] = JHtml::_('select.option', "", "/");

		foreach ($folders as $folder)
		{
			$folder		= str_replace($com_media_base_uri, "", str_replace(DIRECTORY_SEPARATOR, '/', $folder));
			$value		= substr($folder, 1);
			$text		= str_replace(DIRECTORY_SEPARATOR, "/", $folder);
			$options[]	= JHtml::_('select.option', $value, $text);
		}

		// Sort the folder list array
		if (is_array($options))
		{
			sort($options);
		}

		// Get asset and author id (use integer filter)
		$input = JFactory::getApplication()->input;
		$asset = $input->get('asset', 0, 'integer');

		// For new items the asset is a string. JAccess always checks type first
		// so both string and integer are supported.
		if ($asset == 0)
		{
			$asset = $input->get('asset', 0, 'string');
		}

		$author = $input->get('author', 0, 'integer');

		// Create the drop-down folder select list
		$list = JHtml::_('select.genericlist', $options, 'folderlist', 'size="1" onchange="ImageManager.setFolder(this.options[this.selectedIndex].value, ' . $asset . ', ' . $author . ')" ', 'value', 'text', $base);

		return $list;
	}

	/**
	 * Construct the folder tree for Media Manager
	 *
	 * @param   string  $base  Base for folder tree
	 *
	 * @return multitype:StdClass
	 *
	 * @since 3.5
	 */
	public function getFolderTree($base = null)
	{
		// Get some paths from the request
		if (empty($base))
		{
			$base = COM_MEDIA_BASE;
		}

		$mediaBase = str_replace(DIRECTORY_SEPARATOR, '/', COM_MEDIA_BASE . '/');

		// Get the list of folders
		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($base, '.', true, true);

		$tree = array();

		foreach ($folders as $folder)
		{
			$folder		= str_replace(DIRECTORY_SEPARATOR, '/', $folder);
			$name		= substr($folder, strrpos($folder, '/') + 1);
			$relative	= str_replace($mediaBase, '', $folder);
			$absolute	= $folder;
			$path		= explode('/', $relative);
			$node		= (object) array('name' => $name, 'relative' => $relative, 'absolute' => $absolute);

			$tmp = &$tree;

			for ($i = 0, $n = count($path); $i < $n; $i++)
			{
				if (!isset($tmp['children']))
				{
					$tmp['children'] = array();
				}

				if ($i == $n - 1)
				{
					// We need to place the node
					$tmp['children'][$relative] = array('data' => $node, 'children' => array());
					break;
				}

				if (array_key_exists($key = implode('/', array_slice($path, 0, $i + 1)), $tmp['children']))
				{
					$tmp = &$tmp['children'][$key];
				}
			}
		}
		$tree['data'] = (object) array('name' => JText::_('COM_MEDIA_MEDIA'), 'relative' => '', 'absolute' => $base);

		return $tree;
	}

	public function getForm($data = array(), $loadData = true)
	{
		return;
	}

	/**
	 * Create a table record for a media in table
	 *
	 * @param   JObject  $file  Instance contain media information
	 *
	 * @return boolean  Record created in the table or not
	 *
	 * @since 3.5
	 */
	public function create($file)
	{

		$row = JTable::getInstance('Corecontent');

		// Get type_id fron content_type table
		$type = new JUcmType;
		$typeId = $type->getTypeId('com_media.image');

		// Get relative path
		$rel_path = str_replace(JPATH_ROOT, "", $file['filepath']);

		$data = array();
		$data['core_urls'] = $rel_path;

		$fname = explode('.', $file['name']);
		$data['core_type_id'] = $typeId;
//		$data['core_content_item_id'] = $typeId;
		$data['core_type_alias'] = 'com_media.image';
		$data['core_title'] = $fname[0];
		$data['core_alias'] = JFilterOutput::stringURLSafe($fname[0]);
		$data['core_state'] = '1';

		$metadata = new stdClass;
		$metadata->name 	= $file['name'];
		$metadata->type 	= $file['type'];
		$metadata->filepath = $rel_path;
		$metadata->size 	= $file['size'];
		$data['core_metadata'] = json_encode($metadata);

		$row->bind($data);

		if ($row->store())
		{
			// Need to fix _ucm_base & _ucm_content tables here
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query 	-> select($db->quoteName('core_content_id'))
			-> from($db->quoteName('#__ucm_content'))
			-> where($db->quoteName('core_urls') . ' = ' . $db->quote($rel_path));

			$db->setQuery($query);
			$result = $db->loadObject();
			$pk = $result->core_content_id;

			$query = $db->getQuery(true);
			$query -> update($db->quoteName('#__ucm_content'))
			-> set($db->quoteName('core_content_item_id') . ' = ' . $db->quote($pk))
			-> where($db->quoteName('core_content_id') . ' = ' . $db->quote($pk));

			$db->setQuery($query);
			$db->execute();

			$query = $db->getQuery(true);
			$query -> update($db->quoteName('#__ucm_base'))
			-> set($db->quoteName('ucm_item_id') . ' = ' . $db->quote($pk))
			-> where($db->quoteName('ucm_id') . ' = ' . $db->quote($pk));

			$db->setQuery($query);
			$db->execute();
			
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 * Delete a media entry from table
	 *
	 * @param   string  $url  Path of the media in file system
	 *
	 * @return void
	 *
	 * @since 3.5
	 */
	public function deleteMediaFromTable($url)
	{
		// Get relative path
		$url = str_replace(JPATH_ROOT, "", $url);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query 	-> select($db->quoteName('core_content_id'))
		-> from($db->quoteName('#__ucm_content'))
		-> where($db->quoteName('core_urls') . ' = ' . $db->quote($url));

		$db->setQuery($query);

		$result = $db->loadObject();

		$pk = $result->core_content_id;

		$row = JTable::getInstance('Corecontent');
		$row->delete($pk);

	}
}
