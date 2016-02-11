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
	public $language = English::class;

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
	 * The base module to use (FQN)
	 *
	 * @var string
	 */
	public $base_module = Html5BaseModule::class;

	/**
	 * The parser extension to use (FQN)
	 *
	 * @var string
	 */
	public $parser_extension = ParserExtension::class;

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

}
