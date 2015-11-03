<?php
/**
 * Class BBTemp
 *
 * @filesource   BBTemp.php
 * @created      16.10.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

/**
 * Holds the encoder's temporary data during transfer parser -> module
 *
 * @todo rename?
 *
 * @see \chillerlan\bbcode\Parser::_parse()
 * @see \chillerlan\bbcode\Modules\BaseModule::set_bbtemp()
 */
class BBTemp{

	/**
	 * The current bbcode tag
	 *
	 * @var string
	 */
	public $tag = '';

	/**
	 * An array of the bbcode's attributes
	 *
	 * @var array
	 */
	public $attributes = [];

	/**
	 * The content between the current bbcode tags
	 *
	 * @var string
	 */
	public $content = '';

	/**
	 * The parser options
	 *
	 * @var \chillerlan\bbcode\ParserOptions
	 */
	public $options;

	/**
	 * The current callback depth
	 *
	 * @var int
	 */
	public $depth;

}
