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
	protected $noparse_tags = [];

	/**
	 * @var string
	 */
	protected $eol_token = PHP_EOL;

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
		$this->tag = $bbtemp->tag;
		$this->attributes = $bbtemp->attributes;
		$this->content = $bbtemp->content;
		$this->options = $bbtemp->options;

		return $this;
	}

	/**
	 * Returns a list of the BaseModule's modules
	 *
	 * @return array
	 */
	public function get_modules(){
		return $this->modules;
	}

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return array an array of tagnames
	 * @see \chillerlan\bbcode\Modules\ModuleInterface
	 */
	public function get_tags(){
		return $this->tags;
	}

	/**
	 * Returns an array of noparse tags
	 *
	 * @return array
	 * @see \chillerlan\bbcode\Modules\ModuleInterface
	 */
	public function get_noparse_tags(){
		return $this->noparse_tags;
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
	public function get_eol_token(){
		return $this->eol_token;
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
		$eol = $eol ?: $this->eol_token;
		$this->content = str_replace('__BBEOL__', $eol, $this->content);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function clear_pseudo_tags(){
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
