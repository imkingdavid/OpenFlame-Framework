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
 * OpenFlame Framework - File instance
 * 	     A powerfull class to drive file objects in an effort to provide 
 *       validation services and file storage closures.
 *
 *
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 */
class Instance
{
	/*
	 * @var $_FILEs array
	 */
	private $files = array();

	/*
	 * @var MIME type
	 */
	private $mimetype = '';

	/*
	 * @param string name - input field name
	 * @throws LogicException - This will only happen if they try to create an 
	 *	instance directly (as this does not handle non-existant files, only the
	 *	Handler does).
	 */
	public function __construct($name)
	{
		// Make sure we got it, there is a chance they will not on some windows systems.
		if (!class_exists('finfo'))
		{
			throw new LogicException('Fileinfo extention is not installed. It is required to properly validate images');
		}

		if (!isset($_FILES[$name]) || !sizeof($_FILES[$name]))
		{
			throw new LogicException('File to be uploaded does not exist in the post data.');
		}

		// The advantage here (while not completely foolproof) will examine the
		// file itself and hunt for magic bytes that indicate a mimetype. It is
		// still good practice to access all uploaded file via some 
		// intermediate PHP script to send proper headers.
		$file = new finfo(FILEINFO_MIME_TYPE);
		$this->mimetype = $file->file($this->files['tmp_name']);

		$this->files = $_FILES[$name];
	}

	/*
	 * Get the filesize
	 * @param string format - B: Bytes, KiB: Kibibytes, MiB: Mebibytes, GiB: Gibibytes, TiB: Tibibytes
	 * @return float 
	 */
	public function getSize($format = 'B', $roundDecimal = 2)
	{
		// Find our divisor, defaults to Bytes
		$divisor = 1;
		switch($format)
		{
			case 'B':	$divisor = 1;				break;
			case 'KiB':	$divisor = 1000;			break;
			case 'MiB':	$divisor = 1000000;			break;
			case 'GiB':	$divisor = 1000000000; 		break;
			case 'TiB':	$divisor = 1000000000000;	break;
		}

		$val = (float) ($this->files['size'] / $divisor);
		return round($val, $roundDecimal);
	}

	/*
	 * Is Mime Type
	 *
	 * @param mixed - String (single mine type), or Array for checking against multiple.
	 * @return boolean - Is the file the or one of the mine types in the params?
	 */
	public function isMimeType($mime)
	{
		if (!is_array($mime))
		{
			$mime = array($mime);
		}

		// Loop through and find a match
		foreach($mime as $type)
		{
			$type = trim(strtolower($type));

			if ($type == $this->mimetype)
			{
				return true;
			}
		}

		// If we get to this point, we didn't find a match.
		return false;
	}

	/*
	 * Check to see if it is an image. 
	 * This is more or less a shortcut for check the mime type becuase image
	 * uploading is very common in web applications.
	 *
	 * @return boolean - Is this an image?
	 */
	public function isImage()
	{
		// If the browser isn't even broadcasting an image mime type, we're not
		// even going to bother checking anything else.
		if (strpos($this->files['type'], 'image/') !== 0)
		{
			return false;
		}

		// Now for the real comparison.
		if (strpos($this->mimetype), 'image/') !== 0)
		{
			return false;
		}

		// If they made it this far, they deserve a beer. 
		return true;
	}

	/*
	 * Get the mimetype
	 * @return string - Mimetype
	 */
	public function getMimeType()
	{
		return $this->mimetype;
	}

	/*
	 * Register a closure to store the file
	 * This may be useful if you have special obfuscating techniques or wish to
	 * put it on a remote filesystem.
	 *
	 * @param callback - Function callback. Must take a single parameter, FILES
	 *	Array for the given file.
	 * @return boolean - True on success, false on failure. RELIES ON THE 
	 *	CALLBACK TO GIVE THIS INFORMATION!!!
	 */
	public function store($callback)
	{
		return $callback($this->files);
	}
}
