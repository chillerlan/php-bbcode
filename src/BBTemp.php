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
 * @todo: rename?
 */
class BBTemp{

	/**
	 * @var string
	 */
	public $tag = '';

	/**
	 * @var array
	 */
	public $attributes = [];

	/**
	 * @var string
	 */
	public $content = '';

	/**
	 * @var \chillerlan\bbcode\ParserOptions
	 */
	public $options;

}
