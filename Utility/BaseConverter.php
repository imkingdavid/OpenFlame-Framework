<?php
/**
 *
 * @package     OpenFlame Web Framework
 * @copyright   (c) 2010 OpenFlameCMS.com
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 *
 * Minimum Requirement: PHP 5.3.0
 */
 
namespace OpenFlame\Framework\Utility;

if(!defined('OpenFlame\\ROOT_PATH')) exit;

/**
 * OpenFlame Web Framework - Base Convertor
 * 		OOP interface for converting between different bases of arbitrary charsets.
 *
 *
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 *
 */
class BaseConverter
{
	/*
	 * @var string - convert to charset (defaults to hex)
	 */
	private $charsetTo = array();

	/*
	 * @var string - convert from charset (defaults to dec)
	 */
	private $charsetFrom = array();

	/*
	 * Build-in bases 
	 */
	const BASE_2 	= '01';
	const BASE_10	= '0123456789';
	const BASE_16	= '0123456789ABCDE';

	/*
	 * Get Instance
	 *
	 * @return instance of this class
	 */
	public static function getInstance()
	{
		return new static();
	}

	/*
	 * Set Charset (coverting to)
	 *
	 * @var mixed charset - String of characters for converting to
	 * @return $this
	 */
	public function setCharsetTo($charset)
	{
		$this->charsetTo = is_array($charset) ? $charset : array((string) $charset);
		return $this;
	}

	/*
	 * Set Charset (coverting from)
	 *
	 * @var mixed charset - String of characters for converting from
	 * @return $this	 
	 */
	public function setCharsetFrom($charset)
	{
		$this->charsetFrom = is_array($charset) ? $charset : array((string) $charset);
		return $this;
	}

	/*
	 * Decode to base 10
	 *
	 * @param string - String to be encoded, must be within the charset of 
	 *	charsetTo.
	 * @return string - Base 10 representation of the number 
	 */
	public function decode($input)
	{
		$_charsetTo = array_flip($this->charsetTo);
		$_inputAry = str_split(strrev($input));
		$base = (string) sizeof($_charsetTo);

		$output = '';
		for($i = 0; sizeof($_inputAry) > $i; $i++)
		{
			$output = bcadd($output, bcmul($_charsetTo[$_inputAry[$i]], bcpow($base, $i)));
		}

		return $output;
	}

	/*
	 * Encode to base from base 10
	 *
	 * @param string - base 10 integer
	 * @return string base in the charset of 
	 */
	public function encode($input)
	{
		$output = '';
		$base = (string) sizeof($this->charsetFrom);

		do
		{
			$rem	= bcmod($input, $base);
			$input	= bcdiv($input, $base);

			$output = $this->charsetFrom[(int) $rem] . $output;
		}
		while(bccomp($input, '1') != -1);

		return $output;
	}

	/*
	 * Convert
	 *
	 * @var string convert - The string to convert
	 * @return string - The output string 
	 */
	public function convert($convert)
	{
		return $this->encode($this->decode($convert));
	}
}
