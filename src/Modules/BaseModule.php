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
use chillerlan\bbcode\Modules\BaseModuleInterface;
use chillerlan\bbcode\Modules\Tagmap;
use chillerlan\bbcode\Modules\ModuleInfo;

/**
 *
 */
class BaseModule implements BaseModuleInterface{

	/**
	 * @var string
	 * @see \chillerlan\bbcode\BBTemp::$tag
	 */
	protected $tag;

	/**
	 * @var array
	 * @see \chillerlan\bbcode\BBTemp::$attributes
	 */
	protected $attributes;

	/**
	 * @var string
	 * @see \chillerlan\bbcode\BBTemp::$content
	 */
	protected $content;

	/**
	 * @var \chillerlan\bbcode\ParserOptions
	 * @see \chillerlan\bbcode\BBTemp::$options
	 */
	protected $options;

	/**
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [];

	/**
	 * @var string
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$eol_token
	 */
	protected $eol_token = PHP_EOL;

	/**
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = [];

	/**
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$noparse_tags
	 */
	protected $noparse_tags = [];

	/**
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = [];

	/**
	 * Constructor
	 *
	 * Sets tag, attributes and content
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 */
	public function __construct(BBTemp $bbtemp = null){
		if($bbtemp instanceof BBTemp){
			$this->set_bbtemp($bbtemp);
		}
	}

	/**
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 */
	public function set_bbtemp(BBTemp $bbtemp){
		foreach(['tag', 'attributes', 'content', 'options'] as $tmp){
			$this->{$tmp} = $bbtemp->{$tmp};
		}

		return $this;
	}

	/**
	 * Returns a list of the BaseModule's modules
	 *
	 * @return \chillerlan\bbcode\Modules\ModuleInfo
	 */
	public function get_info(){
		$info = new ModuleInfo;
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
	public function get_tags(){
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
	public function check_tag(){
		if(!$this->tag || !in_array($this->tag, $this->tags)){
			throw new BBCodeException('tag ['.$this->tag.'] not supported.');
		}

		return $this;
	}

	/**
	 * @param string $str
	 * @param string $eol
	 * @param int    $count
	 *
	 * @return string
	 */
	public function eol($str, $eol = '', $count = null){
		return str_replace($this->options->eol_placeholder, $eol, $str, $count);
	}

	/**
	 * @param string $eol
	 *
	 * @return $this
	 */
	public function clear_eol($eol = null){
		$eol = $eol ?: $this->eol_token;
		$this->content = str_replace($this->options->eol_placeholder, $eol, $this->content);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function clear_pseudo_closing_tags(){
		$this->content = preg_replace('#\*\[/('.$this->options->singletags.')]#is', '', $this->content);

		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed  $default
	 *
	 * @return mixed $this->attributes[$name]
	 */
	public function get_attribute($name, $default = false){
		return isset($this->attributes[$name]) && !empty($this->attributes[$name]) ? $this->attributes[$name] : $default;
	}

	/**
	 * @param string $name
	 * @param array  $array
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function attribute_in($name, array $array, $default = false){
		return isset($this->attributes[$name]) && in_array($this->attributes[$name], $array)
			? $default !== false ? $this->attributes[$name] : true
			: $default;
	}

	/**
	 * @param string $name
	 * @param array  $array
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function attribute_key_in($name, array $array, $default = false){
		return isset($this->attributes[$name]) && array_key_exists($this->attributes[$name], $array)
				? $default !== false ? $array[$this->attributes[$name]] : true
				: $default;
	}

	/**
	 * @param array $array
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function tag_in(array $array, $default = false){
		return in_array($this->tag, $array)
				? $default !== false ? $this->tag : true
				: $default;
	}

	/**
	 * @param $content
	 *
	 * @return string
	 */
	public function sanitize($content){
		return 'Implement sanitize() method!';
	}

	/**
	 * shorthand for self::get_attribute('__BBTAG__')
	 *
	 * @param mixed $default
	 *
	 * @return mixed $this->attributes['__BBTAG__']
	 */
	protected function bbtag($default = false){
		return $this->get_attribute($this->options->bbtag_placeholder, $default);
	}

	/**
	 * shorthand for self::attribute_in('__BBTAG__', $array)
	 *
	 * @param array $array
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function bbtag_in(array $array, $default = false){
		return $this->attribute_in($this->options->bbtag_placeholder, $array, $default);
	}

	/**
	 * Called from within a module
	 *
	 * @return string
	 */
	public function transform(){
		$this->check_tag();

		/** @var $this \chillerlan\bbcode\Modules\ModuleInterface */
		return $this->_transform();
	}
}
