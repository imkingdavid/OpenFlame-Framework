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
use OpenFlame\Framework\Upload\MimeType as Type;

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
	 * @var string - MIME type
	 */
	private $mimetype = '';

	/*
	 * @var int - size in bytes
	 */
	private $size = 0;

	/*
	 * @var string - name
	 */
	private $name = '';

	/*
	 * @var string - ext
	 */
	private $ext = '';

	/*
	 * @var string - Temp name as it is stored on the system
	 */
	private $tmpName = '';

	/*
	 * @var int - Error from $_FILES
	 */
	private $error = UPLOAD_ERR_OK;

	/*
	 * @param fileInfo fileInfo - info from $_FILES
	 * @throws LogicException - This will only happen if they try to create an 
	 *	instance directly (as this does not handle non-existant files, only the
	 *	Handler does).
	 * @throws RuntimeException - When there is an issue with the file upload 
	 * 	itself.
	 */
	public function __construct($fileInfo = array())
	{
		// Make sure we got it, there is a chance they will not on some windows systems.
		if (!class_exists('\finfo'))
		{
			throw new \LogicException('Fileinfo extention is not installed. It is required to properly validate images');
		}

		if (!sizeof($fileInfo))
		{
			// You should be using the manager to instance this class, we 
			// should NEVER get here unless they didn't use the manager.
			throw new \LogicException('File to be uploaded does not exist in the post data.');
		}

		// Check for an error
		if ($fileInfo['error'])
		{
			$error = (int) $fileInfo['error'];
			switch($error)
			{
				case UPLOAD_ERR_INI_SIZE:
					$message = 'The uploaded file exceeded the upload_max_filesize directive in your php.ini';
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = 'Uploaded file exceded the size in the form';
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = 'Only part of the file was able to be uploaded';
					break;
				case UPLOAD_ERR_NO_FILE:
					$message = 'No file was uploaded';
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = 'A temp directory could not be found';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = 'Could not write to the disk; you may not have permission to do so';
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = 'A PHP extention has stopped this upload. Examine your phpinfo() output to see what might have stopped it';
					break;
				default:
					$message = 'Unknown Error. Sorry this is such a useless error message.';
					break;
			}

			throw new \RuntimeException("There was error uploading the file: $message", (int) $error);
		}

		// http://blog.kotowicz.net/2011/06/file-path-injection-in-php-536-file.html
		$this->name		= (string) basename($fileInfo['name']);
		$this->size		= (int) $fileInfo['size'];
		$this->ext		= substr(strrchr($this->name, '.'), 1);
		$this->error	= (string) $fileInfo['error'];
		$this->tmpName	= (string) $fileInfo['tmp_name'];

		// The advantage here (while not completely foolproof) will examine the
		// file itself and hunt for magic bytes sequences that indicate a 
		// mimetype. It is still good practice to access all uploaded file via 
		// some intermediate PHP script to send proper headers.
		$file = new \finfo(FILEINFO_MIME_TYPE);
		$this->mimetype = $file->file($this->tmpName);
	}

	/*
	 * Get the filesize
	 * @param string format - B: Bytes, KiB: Kibibytes, MiB: Mebibytes, GiB: Gibibytes
	 * @return float 
	 */
	public function getSize($format = 'B', $roundDecimal = 2)
	{
		// Find our divisor, defaults to Bytes
		$divisor = 1;
		switch($format)
		{
			case 'B':	$divisor = 1;				break;
			case 'KiB':	$divisor = 1024;			break;
			case 'MiB':	$divisor = 1048576;			break;
			case 'GiB':	$divisor = 1073741824; 		break;
		}

		$val = (float) ($this->size / $divisor);
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
	 * Checks for .GIF, .PNG, .JPEG/JPG, or .SVG
	 *
	 * @return boolean - Is this an image?
	 */
	public function isImage()
	{
		return $this->isMimeType(array(Type::IMG_GIF, Type::IMG_JPEG, Type::IMG_PNG, Type::IMG_SVG)) ? true : false;
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
	 * Get the file extention
	 * @return string - Extention
	 */
	public function getExt()
	{
		return $this->ext;
	}

	/*
	 * Get the file name
	 * @return string - file name
	 */
	public function getName()
	{
		return $this->name;
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
		return $callback(array(
			'name'		=> $this->name,
			'size'		=> $this->size,
			'ext'		=> $this->ext,
			'type'		=> $this->mimetype,
			'tmp_name'	=> $this->tmpName,
			'error'		=> $this->error,
		));
	}
}
