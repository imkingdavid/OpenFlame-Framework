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

namespace OpenFlame\Framework\Session\Storage;
use \OpenFlame\Framework\Core;

if(!defined('OpenFlame\\ROOT_PATH')) exit;

/**
 * OpenFlame Web Framework - Sessions Engine interface,
 * 		Sessions engine prototype, declares required methods that a sessions engine must define in order to be valid.
 *
 *
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 */
interface EngineInterface
{
	public function init($_sid, $_uid, $_al);
	public function setCookieName();
	public function setFingerprint();
	public function setRandSeed();
	public function getSessionExpiry();
	public function getData();
	public function getFingerprint();
	public function getLastClickTime();
	public function getRandSeed();
	public function checkAutoLogin();
	public function gc();
}
