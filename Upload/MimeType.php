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
 * OpenFlame Framework - Mime types
 * 	     Container for constant Mime Types
 *
 *
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/OpenFlame/OpenFlame-Framework
 */
class MimeType
{
	/*
	 * Application Types
	 */
	const APP_ATOM	= 'application/atom+xml';
	const APP_JSON	= 'application/json';
	const APP_JS	= 'application/javascript';
	const APP_OCTET	= 'application/octet-stream';
	const APP_PDF	= 'application/pdf';
	const APP_SOAP	= 'application/soap+xml';
	const APP_DTD	= 'application/xml-dtd';
	const APP_ZIP	= 'application/zip';
	const APP_GZ	= 'application/x-gzip';

	/*
	 * Audio Types
	 */
	const AUD_MP4	= 'audio/mp4';
	const AUD_MP3	= 'audio/mpeg';
	const AUD_MPEG	= 'audio/mpeg';
	const AUD_WMA	= 'audio/x-ms-wma';
	const AUD_WAV	= 'audio/vnd.wave';

	/*
	 * Image types
	 */
	const IMG_GIF	= 'image/gif';
	const IMG_JPEG	= 'image/jpeg';
	const IMG_PNG	= 'image/png';
	const IMG_SVG	= 'image/svg+xml';
	const IMG_TIFF	= 'image/tiff';
	const IMG_ICO	= 'image/vnd.microsoft.icon';

	/*
	 * Text types
	 */
	const TXT_CSS			= 'text/css';
	const TXT_CSV			= 'text/csv';
	const TXT_HTML			= 'text/html';
	const TXT_JAVASCRIPT	= 'text/javascript';
	const TXT_JS			= 'text/javascript';
	const TXT_PLAIN			= 'text/plain';
	const TXT_XML			= 'text/xml';

	/*
	 * Video Types
	 */
	const VID_MPEG		= 'video/mpeg';
	const VID_MP4		= 'video/mp4';
	const VID_QICKTIME	= 'video/quicktime';
	const VID_WMV		= 'video/x-ms-wmv';
}