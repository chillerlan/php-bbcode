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
use chillerlan\bbcode\ParserOptions;

/**
 * An empty parser extension as ground to start from
 */
class ParserExtension implements ParserExtensionInterface{

	/**
	 * @var \chillerlan\bbcode\ParserOptions
	 */
	protected $options;

	/**
	 * ParserExtension constructor.
	 *
	 * @param \chillerlan\bbcode\ParserOptions $options
	 */
	public function __construct(ParserOptions $options){
		$this->options = $options;
	}

	/**
	 * Pre-parser
	 *
	 * The bbcode you receive is already sanitized, which means: any replacements you do here won't be sanitized any further. Take care!
	 * Do anything here to the unparsed bbcode, just don't touch newlines - these will be replaced with a placeholder after this step.
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string preparsed bbcode
	 */
	public function pre($bbcode){
		return $bbcode;
	}

	/**
	 * Post-parser
	 *
	 * Use this method in case you want to alter the parsed bbcode.
	 * The newline placeholders are still available and any remaining will be removed in the last step before output
	 *
	 * Example: you want the "img" bbcode to use database images instead of user URLs.
	 * You'd go and change the tag so that it only accepts digits like [img=123456]
	 * and replace any occurence with a unique placeholder like {__IMG#ID__}.
	 * Now the post-parser gets into play: you preg_match_all() out all your placeholders,
	 * grab the images in a single query from the database and replace them with their respective <img> tag
	 * or whatever replacement and any corrupt id with a placeholder image. Profit!
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string postparsed bbcode
	 */
	public function post($bbcode){
		return $bbcode;
	}

}
