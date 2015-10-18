<?php
/**
 * Class ParserExtension
 *
 * @filesource   ParserExtension.php
 * @created      19.09.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

use chillerlan\bbcode\ParserExtensionInterface;

/**
 * An empty preparser as ground to start from
 * Implements pre-/post-parser methods, for example: parsing Smileys
 */
class ParserExtension implements ParserExtensionInterface{

	/**
	 * Do anything here, just don't touch newlines :P
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string preparsed bbcode
	 */
	public function pre($bbcode){
		return $bbcode;
	}

	/**
	 * @param string $bbcode bbcode
	 *
	 * @return string postparsed bbcode
	 */
	public function post($bbcode){
		return $bbcode;
	}

}
