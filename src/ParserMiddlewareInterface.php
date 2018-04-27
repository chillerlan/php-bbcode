<?php
/**
 * Interface ParserMiddlewareInterface
 *
 * @filesource   ParserMiddlewareInterface.php
 * @created      26.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

use chillerlan\Traits\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

interface ParserMiddlewareInterface{

	/**
	 * ParserMiddlewareInterface constructor.
	 *
	 * @param \chillerlan\Traits\ContainerInterface $options
	 * @param \Psr\SimpleCache\CacheInterface  $cache
	 * @param \Psr\Log\LoggerInterface         $logger
	 */
	public function __construct(ContainerInterface $options, CacheInterface $cache, LoggerInterface $logger);

	/**
	 * Pre-parser
	 *
	 * The bbcode you receive is already sanitized, which means: a
	 * ny replacements you do here won't be sanitized any further. Take care!
	 * Do anything here to the unparsed bbcode, just don't touch newlines
	 * - these will be replaced with a placeholder after this step.
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string preparsed bbcode
	 */
	public function pre(string $bbcode):string;

	/**
	 * Post-parser
	 *
	 * Use this method in case you want to alter the parsed bbcode.
	 * The newline placeholders are still available and any remaining will
	 * be removed in the last step before output
	 *
	 * Example: you want the "img" bbcode to use database images instead of user URLs.
	 * You'd replace any occurence with a unique placeholder like {__IMG#ID__}.
	 * Now the post-parser gets into play: you preg_match_all() out all your placeholders,
	 * grab the images in a single query from the database and replace them with their respective <img> tag
	 * or whatever replacement and any corrupt id with a placeholder image. Profit!
	 *
	 * @param string $bbcode bbcode
	 *
	 * @return string postparsed bbcode
	 */
	public function post(string $bbcode):string;

}
