<?php
/**
 * Interface BaseModuleInterface
 *
 * @filesource   BaseModuleInterface.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules;

use \chillerlan\bbcode\BBTemp;

/**
 * Implements the basic functionality for each module
 */
interface BaseModuleInterface{

	/**
	 * Sets self::$tag, self::$attributes, self::$content and self::$options
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 */
	public function setBBTemp(BBTemp $bbtemp);

	/**
	 * Returns a list of the BaseModule's modules
	 *
	 * @return \chillerlan\bbcode\Modules\BaseModuleInfo
	 */
	public function getInfo():BaseModuleInfo;

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return \chillerlan\bbcode\Modules\Tagmap
	 * @see \chillerlan\bbcode\Modules\ModuleInterface
	 */
	public function getTags():Tagmap;

	/**
	 * Checks if the module supports the current tag
	 *
	 * @return $this
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function checkTag();

	/**
	 * Replaces the EOL placeholder in the given string with a custom token
	 *
	 * @param string $str   haystack
	 * @param string $eol   [optional] custom EOL token, default: ''
	 * @param int    $count [optional] replace first $count occurences
	 *
	 * @return string
	 */
	public function eol(string $str, string $eol = '', int $count = null):string;

	/**
	 * Clears all EOL placeholders from self::$content with the base modules EOL token
	 *
	 * @param string $eol [optional] custom EOL token
	 *
	 * @return $this
	 */
	public function clearEOL(string $eol = '');

	/**
	 * Clears all pseudo closing single tag bbcodes like [/br]
	 *
	 * @return $this
	 */
	public function clearPseudoClosingTags();

	/**
	 * Retrieves an attribute's value by it's name
	 *
	 * @param string $name     the desired attributes name
	 * @param mixed  $default  [optional] a default value in case the attribute isn't set, defaults to false
	 *
	 * @return mixed the attribute's value in case it exists, otherwise $default
	 */
	public function getAttribute(string $name, $default = false);

	/**
	 * Retrieves an attribute's value by it's name and checks if it's whitelisted
	 *
	 * @param string $name      the desired attributes name
	 * @param array  $whitelist an array with whitelisted values
	 * @param mixed  $default   [optional] a default value in case the attribute isn't set, defaults to false
	 *
	 * @return mixed boolean if no $default is set, otherwise the attribute's value in case it exists or $default
	 */
	public function attributeIn(string $name, array $whitelist, $default = false);

	/**
	 * Checks if an attribute exists and if it exists as key in a whitelist

	 * @param string $name      the desired attributes name
	 * @param array  $whitelist an array with whitelisted key -> value pairs
	 * @param mixed  $default   [optional] a default value in case the attribute isn't set, defaults to false
	 *
	 * @return mixed boolean if no $default is set, otherwise the whitelist value to the given key in case it exists or $default
	 */
	public function attributeKeyIn(string $name, array $whitelist, $default = false);

	/**
	 * Checks if the current tag is whitelisted
	 *
	 * @param array $whitelist an array with whitelisted tag names
	 * @param mixed $default   [optional] a default value in case the tag isn't whitelisted
	 *
	 * @return mixed boolean if no $default is set, otherwise the whitelisted tag or $default
	 */
	public function tagIn(array $whitelist, $default = false);

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitize(string $content):string;

	/**
	 * Checks the tag and returns the processed bbcode, called from the parser within a module
	 *
	 * @return string
	 * @see \chillerlan\bbcode\Modules\ModuleInterface::transform()
	 * @internal
	 */
	public function transform():string;

	/**
	 * Checks if an URL is valid using filter_var()
	 *
	 * @param string $url the URL to check
	 *
	 * @return bool|string the url if valid, otherwise false
	 */
	public function checkUrl(string $url);

	/**
	 * Wraps the given content between the wrapper. Obvious, eh?
	 *
	 * @param string $content
	 * @param string $wrapper
	 *
	 * @return string
	 */
	public function wrap(string $content, string $wrapper):string;
}
