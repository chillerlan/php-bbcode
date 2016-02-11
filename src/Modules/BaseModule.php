<?php
/**
 * Class BaseModule
 *
 * @filesource   BaseModule.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules;

use chillerlan\bbcode\BBCodeException;
use chillerlan\bbcode\BBTemp;

/**
 * The base module implements the basic functionality for each module
 */
class BaseModule implements BaseModuleInterface{

	/**
	 * The current bbcode tag
	 *
	 * @var string
	 * @see \chillerlan\bbcode\BBTemp::$tag
	 */
	protected $tag;

	/**
	 * An array of the bbcode's attributes
	 *
	 * @var array
	 * @see \chillerlan\bbcode\BBTemp::$attributes
	 */
	protected $attributes;

	/**
	 * The content between the current bbcode tags
	 *
	 * @var string
	 * @see \chillerlan\bbcode\BBTemp::$content
	 */
	protected $content;

	/**
	 * The parser options
	 *
	 * @var \chillerlan\bbcode\ParserOptions
	 * @see \chillerlan\bbcode\BBTemp::$options
	 */
	protected $options;

	/**
	 * Holds the translation class for the current language
	 *
	 * @var \chillerlan\bbcode\Language\LanguageInterface
	 */
	protected $language;

	/**
	 * The current callback depth
	 *
	 * @var int
	 * @see \chillerlan\bbcode\BBTemp::$depth
	 */
	protected $depth;

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [];

	/**
	 * Holds the current base module's EOL token which will replace any newlines
	 *
	 * @var string
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$eol_token
	 */
	protected $eol_token = PHP_EOL;

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = [];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "noparse"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$noparse_tags
	 */
	protected $noparse_tags = [];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "single tag"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = [];

	/**
	 * Constructor
	 *
	 * calls self::setBBTemp() in case $bbtemp is set
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 */
	public function __construct(BBTemp $bbtemp = null){
		if($bbtemp instanceof BBTemp){
			$this->setBBTemp($bbtemp);
		}
	}

	/**
	 * Sets self::$tag, self::$attributes, self::$content and self::$options
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 */
	public function setBBTemp(BBTemp $bbtemp){
		foreach(['tag', 'attributes', 'content', 'options', 'language', 'depth'] as $var){
			$this->{$var} = $bbtemp->{$var};
		}

		return $this;
	}

	/**
	 * Returns a list of the BaseModule's modules
	 *
	 * @return \chillerlan\bbcode\Modules\BaseModuleInfo
	 */
	public function getInfo(){
		$info = new BaseModuleInfo;
		foreach(['modules', 'eol_token'] as $option){
			$info->{$option} = $this->{$option};
		}

		return $info;
	}

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return \chillerlan\bbcode\Modules\Tagmap
	 * @see \chillerlan\bbcode\Modules\ModuleInterface
	 */
	public function getTags(){
		$tags = new Tagmap;
		$tags->tags = $this->tags;
		$tags->noparse_tags = $this->noparse_tags;
		$tags->singletags = $this->singletags;
		return $tags;
	}

	/**
	 * Checks if the module supports the current tag
	 *
	 * @return $this
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function checkTag(){
		if(!$this->tag || !in_array($this->tag, $this->tags)){
			throw new BBCodeException('tag ['.$this->tag.'] not supported.');
		}

		return $this;
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
	public function eol($str, $eol = '', $count = null){
		return str_replace($this->options->eol_placeholder, $eol, $str, $count);
	}

	/**
	 * Clears all EOL placeholders from self::$content with the base modules EOL token
	 *
	 * @param string $eol [optional] custom EOL token
	 *
	 * @return $this
	 */
	public function clearEol($eol = null){
		$eol = $eol ?: $this->eol_token;
		$this->content = str_replace($this->options->eol_placeholder, $eol, $this->content);

		return $this;
	}

	/**
	 * Clears all pseudo closing single tag bbcodes like [/br]
	 *
	 * @return $this
	 */
	public function clearPseudoClosingTags(){
		$this->content = preg_replace('#\[/('.$this->options->singletags.')]#is', '', $this->content);

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
	public function getAttribute($name, $default = false){
		return isset($this->attributes[$name]) && !empty($this->attributes[$name]) ? $this->attributes[$name] : $default;
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
	public function attributeIn($name, array $whitelist, $default = false){
		return isset($this->attributes[$name]) && in_array($this->attributes[$name], $whitelist)
			? $default !== false ? $this->attributes[$name] : true
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
	public function attributeKeyIn($name, array $whitelist, $default = false){
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
	public function tagIn(array $whitelist, $default = false){
		return in_array($this->tag, $whitelist)
				? $default !== false ? $this->tag : true
				: $default;
	}

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 * @codeCoverageIgnore
	 */
	public function sanitize($content){
		return 'Implement sanitize() method!';
	}

	/**
	 * Checks the tag and returns the processed bbcode, called from the parser within a module
	 *
	 * @return string
	 *
	 * @see \chillerlan\bbcode\Modules\ModuleInterface::transform()
	 */
	public function transform(){
		$this->checkTag();

		/** @var $this \chillerlan\bbcode\Modules\ModuleInterface */
		return $this->__transform();
	}

	/**
	 * shorthand for self::getAttribute('__BBTAG__')
	 *
	 * @param mixed $default
	 *
	 * @return mixed $this->attributes['__BBTAG__']
	 */
	protected function bbtag($default = false){
		return $this->getAttribute($this->options->bbtag_placeholder, $default);
	}

	/**
	 * shorthand for self::attributeIn('__BBTAG__', $array)
	 *
	 * @param array $array
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function bbtag_in(array $array, $default = false){
		return $this->attributeIn($this->options->bbtag_placeholder, $array, $default);
	}

	/**
	 * Checks if an URL is valid using filter_var()
	 *
	 * @param string $url the URL to check
	 *
	 * @return bool|string the url if valid, otherwise false
	 */
	public function checkUrl($url){
		if(filter_var($url, FILTER_VALIDATE_URL) === false){
			return false;
		}
		// todo: check against whitelist?

		return $url;
	}

	/**
	 * Wraps the given content between the wrapper. Obvious, eh?
	 *
	 * @param string $content
	 * @param string $wrapper
	 *
	 * @return string
	 */
	public function wrap($content, $wrapper){
		return $wrapper.$content.$wrapper;
	}

}
