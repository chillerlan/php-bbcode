<?php
/**
 * Class ExampleParserExtension
 *
 * @filesource   ExampleParserExtension.php
 * @created      19.09.2015
 * @package      Example
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace Example;

use chillerlan\bbcode\ParserExtensionInterface;

/**
 * An empty preparser as ground to start from
 * Implements pre-/post-parser methods, for example: parsing Smileys
 */
class ExampleParserExtension implements ParserExtensionInterface{

	/**
	 * Do anything here, just don't touch newlines :P
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string preparsed bbcode
	 */
	public function pre($bbcode){

		$search = [
			"\t", // lets convert all tabs into 4 spaces
		    '{__BASE_URL__}', // assume we use a special token for our base url
		];

		$replace = [
			'    ',
		    'https://your.base/url/'
		];

		return str_replace($search, $replace, $bbcode);
	}

	/**
	 * The newline placeholders __BBEOL__ are still available and any remaining will be removed in the last step before output
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string postparsed bbcode
	 */
	public function post($bbcode){
		return $bbcode;
	}

}
