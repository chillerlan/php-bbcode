<?php
/**
 * Interface ParserExtensionInterface
 *
 * @filesource   ParserExtensionInterface.php
 * @created      19.09.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

/**
 * Implements pre-/post-parser methods, for example: parsing Smileys
 */
interface ParserExtensionInterface{

	/**
	 * Do anything here, just don't touch newlines :P
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string preparsed bbcode
	 */
	public function pre($bbcode);

	/**
	 * The newline placeholders __BBEOL__ are still available and any remaining will be removed in the last step before output
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string postparsed bbcode
	 */
	public function post($bbcode);
}
