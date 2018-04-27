<?php
/**
 * Class BBCodeModuleAbstract
 *
 * @filesource   BBCodeModuleAbstract.php
 * @created      24.04.2018
 * @package      chillerlan\BBCode\Output
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output;

use chillerlan\Traits\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class BBCodeModuleAbstract implements BBCodeModuleInterface{

	protected $tag;
	protected $attributes;
	protected $content;
	protected $match;
	protected $callback_count;

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 */
	protected $tags = [];

	/**
	 * Holds an array of singletags
	 *
	 * @var array
	 */
	protected $singletags = [];

	/**
	 * Holds an array of noparse tags
	 *
	 * @var array
	 */
	protected $noparse = [];

	/**
	 * @var \chillerlan\BBCode\BBCodeOptions
	 */
	protected $options;

	/**
	 * @var \Psr\SimpleCache\CacheInterface
	 */
	protected $cache;

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	public function __construct(ContainerInterface $options, CacheInterface $cache, LoggerInterface $logger){
		$this->options = $options;
		$this->cache   = $cache;
		$this->logger  = $logger;
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return string
	 */
	public function __call($name, $arguments):string{

		if(in_array($name, $this->tags, true)){
			[$this->tag, $this->attributes, $this->content, $this->match, $this->callback_count] = $arguments;

			if(method_exists($this, $name)){
				return call_user_func([$this, $name]);
			}

			return $this->transform();
		}

		return  '';//$this->match;
	}

	/**
	 * this is the catch-all method for __call()
	 *
	 * @inheritdoc
	 */
	protected function transform():string{
		return $this->match; // do nothing
	}

	/**
	 * @inheritdoc
	 */
	public function getTags():array {
		return $this->tags;
	}

	/**
	 * @inheritdoc
	 */
	public function getSingleTags():array {
		return $this->singletags;
	}

	/**
	 * @inheritdoc
	 */
	public function getNoparse():array {
		return $this->noparse;
	}

	/**
	 * @return string
	 */
	protected function randomID():string {
		return hash('crc32b', random_bytes(64));
	}

	/**
	 * Clears all EOL placeholders from self::$content with the base modules EOL token
	 *
	 * @param string $eol [optional] custom EOL token
	 *
	 * @return $this
	 */
	protected function clearEOL(string $eol = null){
		$eol = $eol ?? PHP_EOL;
		$this->content = str_replace($this->options->placeholder_eol, $eol, $this->content);

		return $this;
	}

	/**
	 * Clears all pseudo closing single tag bbcodes like [/br]
	 *
	 * @return $this
	 */
	protected function clearPseudoClosingTags(){
		$this->content = preg_replace('#\[/('.implode('|', array_merge(['br', 'hr'], $this->singletags)).')]#is', '', $this->content);

		return $this;
	}
	/**
	 * Retrieves an attribute's value by it's name
	 *
	 * @param string $name     the desired attributes name
	 * @param mixed  $default  [optional] a default value in case the attribute isn't set, defaults to false
	 *
	 * @return mixed the attribute's value in case it exists, otherwise $default
	 */
	protected function getAttribute(string $name, $default = false){
		return isset($this->attributes[$name]) && !empty($this->attributes[$name]) ? $this->attributes[$name] : $default;
	}

	/**
	 * shorthand for self::getAttribute('__BBTAG__')
	 *
	 * @param mixed $default
	 *
	 * @return mixed $this->attributes['__BBTAG__']
	 */
	protected function bbtag($default = false){
		return $this->getAttribute($this->options->placeholder_bbtag, $default);
	}

	/**
	 * shorthand for self::attributeIn('__BBTAG__', $array)
	 *
	 * @param array $array
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function bbtagIn(array $array, $default = false){
		return $this->attributeIn($this->options->placeholder_bbtag, $array, $default);
	}

	/**
	 * Replaces the EOL placeholder in the given string with a custom token
	 *
	 * @param string $str   haystack
	 * @param string $eol   [optional] custom EOL token, default: ''
	 * @param int    $count [optional] replace first $count occurences
	 *
	 * @return string
	 */
	protected function eol(string $str, string $eol = '', int $count = null):string{
		return str_replace($this->options->placeholder_eol, $eol, $str, $count);
	}

	/**
	 * Retrieves an attribute's value by it's name and checks if it's whitelisted
	 *
	 * @param string $name      the desired attributes name
	 * @param array  $whitelist an array with whitelisted values
	 * @param mixed  $default   [optional] a default value in case the attribute isn't set, defaults to false
	 *
	 * @return mixed boolean if no $default is set, otherwise the attribute's value in case it exists and is whitelisted or $default
	 */
	protected function attributeIn(string $name, array $whitelist, $default = false){
		return isset($this->attributes[$name]) && in_array($this->attributes[$name], $whitelist)
			? $default !== false
				? $this->attributes[$name]
				: true
			: $default;
	}

	/**
	 * Checks if an attribute exists and if it exists as key in a whitelist

	 * @param string $name      the desired attributes name
	 * @param array  $whitelist an array with whitelisted key -> value pairs
	 * @param mixed  $default   [optional] a default value in case the attribute isn't set, defaults to false
	 *
	 * @return mixed boolean if no $default is set, otherwise the whitelist value to the given key in case it exists or $default
	 */
	protected function attributeKeyIn(string $name, array $whitelist, $default = false){
		return isset($this->attributes[$name]) && array_key_exists($this->attributes[$name], $whitelist)
			? $default !== false ? $whitelist[$this->attributes[$name]] : true
			: $default;
	}

	/**
	 * Checks if the current tag is whitelisted
	 *
	 * @param array $whitelist an array with whitelisted tag names
	 * @param mixed $default   [optional] a default value in case the tag isn't whitelisted
	 *
	 * @return mixed boolean if no $default is set, otherwise the whitelisted tag or $default
	 */
	protected function tagIn(array $whitelist, $default = false){
		return in_array($this->tag, $whitelist)
			? $default !== false ? $this->tag : true
			: $default;
	}


}
