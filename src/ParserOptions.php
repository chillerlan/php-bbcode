<?php
/**
 * Class ParserOptions
 *
 * @filesource   ParserOptions.php
 * @created      21.10.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

use chillerlan\bbcode\Language\English;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;

/**
 * Holds the user defined options
 *
 * @see \chillerlan\bbcode\Parser::__construct()
 */
class ParserOptions{

	/**
	 * The language class to use (FQN)
	 *
	 * @var string
	 */
	public $languageInterface = English::class;

	/**
	 * The base module to use (FQN)
	 *
	 * @var string
	 */
	public $baseModuleInterface = Html5BaseModule::class;

	/**
	 * The parser extension to use (FQN)
	 *
	 * @var string
	 */
	public $parserExtensionInterface = ParserExtension::class;

	/**
	 * CA Root Certificates for use with CURL/SSL
	 *
	 * @var string
	 * @link http://init.haxx.se/ca/cacert.pem
	 */
	public $ca_info = null;

	/**
	 * Google API key. Used by the Video module to get info and thumbnails from youtube videos.
	 *
	 * @link https://console.developers.google.com/apis/credentials -> server key
	 *
	 * @var string
	 */
	public $google_api_key = null;

	/**
	 * Vimeo API access token.
	 *
	 * @link https://developer.vimeo.com/apps/[YOUR_APP_ID]#authentication
	 *
	 * @var string
	 */
	public $vimeo_access_token = null;

	/**
	 * Input sanitizing
	 *
	 * You really don't want to set it to false, do you?
	 * Set to false in case you use something like HTML purifier or a non-markup output module.
	 *
	 * @var bool
	 */
	public $sanitize = true;

	/**
	 * Nesting limit
	 *
	 * Note: setting this value too high may either lock up your CPU for a while or crash PHP.
	 *       100 is plenty (imagine quote wars!).
	 *
	 * @var int
	 */
	public $nesting_limit = 100;

	/**
	 * The EOL placeholder token
	 *
	 * Change in case you use this token somewhere in your bbcode
	 *
	 * @var string
	 */
	public $eol_placeholder = '__BBEOL__';

	/**
	 * The attribute key name of the tag attribute
	 *
	 * [bbtag=val attr0=val...]...[/bbtag]
	 *
	 * @var string
	 */
	public $bbtag_placeholder = '__BBTAG__';

	/**
	 * An array of allowed tagnames
	 *
	 * @var array
	 */
	public $allowed_tags = [];

	/**
	 * Determines wether to allow all or no bbcodes in case self::$allowed_tags is empty
	 *
	 * @var bool
	 */
	public $allow_all = false;

	/**
	 * The match string for singletags
	 *
	 * (tag0|tag1|...)
	 *
	 * @var string
	 * @see \chillerlan\bbcode\Parser::__construct()
	 * @see \chillerlan\bbcode\Parser::parse()
	 * @see \chillerlan\bbcode\Modules\BaseModule::clearPseudoClosingTags()
	 * @internal
	 */
	public $singletags;

	/**
	 * The current EOL token
	 *
	 * @var string
	 * @see \chillerlan\bbcode\Parser::__construct()
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$eol_token
	 * @see \chillerlan\bbcode\Modules\BaseModule::$eol_token
	 * @internal
	 */
	public $eol_token;

	/**
	 * @var string fqcn -> \chillerlan\Database\Drivers\DBDriverInterface
	 */
	public $DBDriver = null;

	/**
	 * @var \chillerlan\Database\DBOptions
	 */
	public $DBOptions = null;

}
