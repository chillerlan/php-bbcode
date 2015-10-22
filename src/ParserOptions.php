<?php
/**
 *
 * @filesource   ParserOptions.php
 * @created      21.10.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

/**
 * Class ParserOptions
 */
class ParserOptions{

	/**
	 * @var bool $sanitize_html you really don't want to set it to false, do you?
	 *                            set to false in case you use something like HTML purifier or
	 *                            a non-markup output module
	 */
	public $sanitize = true;

	/**
	 * @var int
	 */
	public $nesting_limit = 100;

	/**
	 * @var string
	 */
	public $eol_placeholder = '__BBEOL__';

	/**
	 * @var string
	 */
	public $bbtag_placeholder = '__BBTAG__';

	/**
	 * @var string
	 */
	public $base_module = __NAMESPACE__.'\\Modules\\Html5\\Html5BaseModule';

	/**
	 * @var string
	 */
	public $parser_extension = __NAMESPACE__.'\\ParserExtension';

	/**
	 * @var string
	 * @todo: improve, <col> may cause a crash...
	 */
	public $singletags;

	/**
	 * @var
	 * @see \chillerlan\bbcode\Modules\ModuleInfo
	 */
	public $eol_token;

}
