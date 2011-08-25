<?php
/**
 *
 * @package     openflame-framework
 * @subpackage  uploader
 * @copyright   (c) 2010 - 2011 openflame-project.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 *
 * Minimum Requirement: PHP 5.3.0
 */

namespace OpenFlame\Framework\Upload;
use OpenFlame\Framework\Core;

/**
 * OpenFlame Framework - File Upload Instance Handler
 *       An easy and secure way to accept file uploads.
 *
 *
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 */
class Handler
{
	/*
	 * @param string name - Name of the input field that contains the file
	 * @return mixed - Instance of \OpenFlame\Framework\Upload\Instance on success, NULL if the file does not exist.
	 */
	public function inputFile($name)
	{
		if (!isset($_FILES[$name]) || !sizeof($_FILES[$name]))
		{
			return NULL;
		}

		// I typically hate modifying original data, but this has to be done.
		// http://blog.kotowicz.net/2011/06/file-path-injection-in-php-536-file.html
		$_FILES[$name]['name'] = basename($_FILES[$name]['name']);

		return new Instance($name);
	}
}
