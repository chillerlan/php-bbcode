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

/**
 *
 */
class BaseModule implements BaseModuleInterface{

	/**
	 * @var string
	 */
	protected $tag;

	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var array
	 */
	protected $modules = [];

	/**
	 * @var array
	 */
	protected $tags = [];

	/**
	 * @var array
	 */
	protected $noparse = [];

	/**
	 * @var string
	 */
	protected $_eol = PHP_EOL;

	/**
	 * holds the encoder module
	 *
	 * @var \chillerlan\bbcode\Modules\ModuleInterface
	 */
	private $encoder;

	/**
	 * Constructor
	 *
	 * Sets tag, attributes and content
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbcode
	 */
	public function __construct(BBTemp $bbcode = null){
		if($bbcode instanceof BBTemp){
			$this->tag = $bbcode->tag;
			$this->attributes = $bbcode->attributes;
			$this->content = $bbcode->content;
			$this->options = $bbcode->options;
		}
	}

	/**
	 * Returns an array of tag -> module
	 * @return array
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function get_tagmap(){
		$tags = [];
		$noparse = [];
		foreach($this->modules as $module){
			if(class_exists($module)){
				$this->encoder = new $module(new BBTemp);
				if($this->encoder instanceof ModuleInterface){
					foreach($this->encoder->_get_tags() as $tag){
						$tags[$tag] = $module;
					}
					$noparse = array_merge($noparse, $this->encoder->_get_noparse_tags());
				}
				else{
					throw new BBCodeException($module.' is not of type ModuleInterface');
				}
			}
		}

		return ['tags' => $tags, 'noparse' => $noparse];
	}

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return array an array of tagnames
	 * @internal
	 * @see \chillerlan\bbcode\Modules\ModuleInterface
	 */
	public function _get_tags(){
		return $this->tags;
	}

	/**
	 * Returns an array of noparse tags
	 *
	 * @return array
	 * @internal
	 * @see \chillerlan\bbcode\Modules\ModuleInterface
	 */
	public function _get_noparse_tags(){
		return $this->noparse;
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
	 * @return string
	 */
	public function get_eol(){
		return $this->_eol;
	}

	/**
	 * @param string $str
	 * @param string $eol
	 * @param int    $count
	 *
	 * @return string
	 */
	public function eol($str, $eol = '', $count = null){
		return str_replace('__BBEOL__', $eol, $str, $count);
	}

	/**
	 * @param string $eol
	 *
	 * @return $this
	 */
	public function clear_eol($eol = null){
		$eol = $eol ?: $this->_eol;
		$this->content = str_replace('__BBEOL__', $eol, $this->content);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function close_pseudo_tags(){
		$this->content = preg_replace('#\*\[/(br|hr|clear)]#is', '', $this->content);

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
		return $this->get_attribute('__BBTAG__', $default);
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
		return $this->attribute_in('__BBTAG__', $array, $default);
	}

}
