<?php
/**
 * Class Parser
 *
 * @version      1.1.0
 * @date         03.11.2015
 *
 * @filesource   Parser.php
 * @created      18.09.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

use chillerlan\bbcode\BBCodeException;
use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\ParserExtension;
use chillerlan\bbcode\ParserExtensionInterface;
use chillerlan\bbcode\ParserOptions;
use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;
use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Regexp BBCode parser
 *
 * idea and regexes from developers-guide.net years ago
 *
 * @link http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html
 */
class Parser{

	/**
	 * Holds the preparsed BBCode
	 *
	 * @var string
	 */
	public $bbcode_pre;

	/**
	 * Holds the parser options
	 *
	 * @var \chillerlan\bbcode\ParserOptions
	 * @see \chillerlan\bbcode\Modules\Tagmap::$options
	 */
	protected $options;

	/**
	 * Map of Tag -> Module
	 *
	 * @var array
	 */
	protected $tagmap = [];

	/**
	 * Holds an array of noparse tags
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$noparse_tags
	 */
	protected $noparse_tags = [];

	/**
	 * Holds an array of allowed tags
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$allowed_tags
	 */
	protected $allowed_tags = [];

	/**
	 * Holds the base module instance
	 *
	 * @var \chillerlan\bbcode\Modules\BaseModuleInterface
	 */
	protected $_base_module;

	/**
	 * Holds the current encoder module
	 *
	 * @var \chillerlan\bbcode\Modules\ModuleInterface
	 */
	protected $_module;

	/**
	 * Holds an array of encoder module instances
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $_modules = [];

	/**
	 * Holds the parser extension instance
	 *
	 * @var \chillerlan\bbcode\ParserExtensionInterface
	 */
	protected $_parser_extension;

	/**
	 * Holds a BBTemp instance
	 *
	 * @var \chillerlan\bbcode\BBTemp
	 */
	protected $_bbtemp;

	/**
	 * testing...
	 *
	 * @var array
	 */
	protected $preg_error = [
		PREG_INTERNAL_ERROR        => 'PREG_INTERNAL_ERROR',
		PREG_BACKTRACK_LIMIT_ERROR => 'PREG_BACKTRACK_LIMIT_ERROR',
		PREG_RECURSION_LIMIT_ERROR => 'PREG_RECURSION_LIMIT_ERROR',
		PREG_BAD_UTF8_ERROR        => 'PREG_BAD_UTF8_ERROR',
		PREG_BAD_UTF8_OFFSET_ERROR => 'PREG_BAD_UTF8_OFFSET_ERROR',
		6                          => 'PREG_JIT_STACKLIMIT_ERROR', // int key to prevent a notice in php 5
	];

	/**
	 * Constructor.
	 *
	 * @param \chillerlan\bbcode\ParserOptions $options [optional]
	 */
	public function __construct(ParserOptions $options = null){
		$this->setOptions(!$options ? new ParserOptions : $options);
		$this->_bbtemp = new BBTemp;
	}


	/**
	 * A simple class loader
	 *
	 * @param string $class  class FQCN
	 * @param string $type   type/interface/inheritor FQCN
	 *
	 * @param mixed  $params [optional] the following arguments are optional and will be passed to the class constructor if present.
	 *
	 * @link https://github.com/chillerlan/framework/blob/master/src/Core/Traits/ClassLoaderTrait.php
	 *
	 * @return object of type $interface
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	private function __loadClass($class, $type, ...$params){ // phpDocumentor stumbles across the ... syntax
		if(class_exists($class)){
			$object = new $class(...$params);

			if(!is_a($object, $type)){
				throw new BBCodeException(get_class($object).' does not implement or inherit '.$type);
			}

			return $object;
		}

		throw new BBCodeException($class.' does not exist');
	}

	/**
	 * Sets the parser options
	 *
	 * @param \chillerlan\bbcode\ParserOptions $options
	 *
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function setOptions($options){
		if(!is_a($options, ParserOptions::class)){
			throw new BBCodeException('Invalid options!');
		}

		$this->options = $options;

		$this->_base_module = $this->__loadClass($this->options->base_module, BaseModuleInterface::class);

		if($this->options->parser_extension){
			$this->_parser_extension = $this->__loadClass($this->options->parser_extension, ParserExtensionInterface::class, $this->options);
		}

		$module_info = $this->_base_module->get_info();
		$singletags = [];
		foreach($module_info->modules as $module){
			$this->_module = $this->__loadClass($module, ModuleInterface::class);

			$tagmap = $this->_module->get_tags();
			foreach($tagmap->tags as $tag){
				$this->tagmap[$tag] = $module;
			}

			$this->_modules[$module] = $this->_module;
			$this->noparse_tags = array_merge($this->noparse_tags, $tagmap->noparse_tags);
			$singletags = array_merge($singletags, $tagmap->singletags);
		}

		$this->options->eol_token = $module_info->eol_token;
		$this->options->singletags = implode('|', $singletags);

		if(is_array($this->options->allowed_tags) && !empty($this->options->allowed_tags)){
			foreach($this->options->allowed_tags as $tag){
				if(array_key_exists($tag, $this->tagmap)){
					$this->allowed_tags[] = $tag;
				}
			}
		}
		else{
			if($this->options->allow_all){
				$this->allowed_tags = array_keys($this->tagmap);
			}
		}
	}

	/**
	 * Returns the current tagmap
	 *
	 * @return array
	 */
	public function getTagmap(){
		ksort($this->tagmap);
		return $this->tagmap;
	}

	/**
	 * Returns the currently allowed tags
	 *
	 * @return array
	 */
	public function getAllowed(){
		sort($this->allowed_tags);
		return $this->allowed_tags;
	}

	/**
	 * Returns the noparse tags
	 *
	 * @return array
	 */
	public function getNoparse(){
		sort($this->noparse_tags);
		return $this->noparse_tags;
	}

	/**
	 * Encodes a BBCode string to HTML (or whatevs)
	 *
	 * @param string $bbcode
	 *
	 * @return string
	 */
	public function parse($bbcode){
		if($this->options->sanitize){
			$bbcode = $this->_base_module->sanitize($bbcode);
		}

		$bbcode = $this->_parser_extension->pre($bbcode);
		// todo: change/move potentially closed singletags before -> base module
		$this->bbcode_pre = $bbcode;
		$bbcode = preg_replace('#\[('.$this->options->singletags.')((?:\s|=)[^]]*)?]#is', '[$1$2][/$1]', $bbcode);
		$bbcode = str_replace(["\r", "\n"], ['', $this->options->eol_placeholder], $bbcode);
		$bbcode = $this->_parse($bbcode);
		$bbcode = $this->_parser_extension->post($bbcode);
		$bbcode = str_replace($this->options->eol_placeholder, $this->options->eol_token, $bbcode);

		return $bbcode;
	}

	/**
	 * strng regexp bbcode killer
	 *
	 * @param string|array $bbcode BBCode as string or matches as array - callback from preg_replace_callback()
	 *
	 * @return string
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	protected function _parse($bbcode){
		static $callback_count = 0;
		$callback = false;
		$preg_error = PREG_NO_ERROR;

		if(is_array($bbcode) && isset($bbcode['tag'], $bbcode['attributes'], $bbcode['content'])){
			$tag = strtolower($bbcode['tag']);
			$attributes = $this->_get_attributes($bbcode['attributes']);
			$content = $bbcode['content'];

			$callback = true;
			$callback_count++;
		}
		else if(is_string($bbcode) && !empty($bbcode)){
			$tag = null;
			$attributes = [];
			$content = $bbcode;
		}
		else{
			return '';
		}

		if($callback_count < (int)$this->options->nesting_limit && !in_array($tag, $this->noparse_tags)){
			$pattern = '#\[(?<tag>\w+)(?<attributes>(?:\s|=)[^]]*)?](?<content>(?:[^[]|\[(?!/?\1((?:\s|=)[^]]*)?])|(?R))*)\[/\1]#';
			$content = preg_replace_callback($pattern, __METHOD__, $content);
			$preg_error = preg_last_error();
		}

		// still testing...
		if($preg_error !== PREG_NO_ERROR){

			throw new BBCodeException('preg_replace_callback() died on ['.$tag.'] due to a '.$this->preg_error[$preg_error]
					.' ('.$preg_error.')'.PHP_EOL.htmlspecialchars(print_r($bbcode, true)));
		}

		if($callback && isset($this->tagmap[$tag]) && in_array($tag, $this->allowed_tags)){
			$this->_bbtemp->tag = $tag;
			$this->_bbtemp->attributes = $attributes;
			$this->_bbtemp->content = $content;
			$this->_bbtemp->options = $this->options;
			$this->_bbtemp->depth = $callback_count;

			$this->_module = $this->_modules[$this->tagmap[$tag]];
			$this->_module->set_bbtemp($this->_bbtemp);
			$content = $this->_module->transform();
		}

		$callback_count = 0;

		return $content;
	}

	/**
	 * The attributes parser
	 *
	 * @todo recognize attributes without value
	 *
	 * @param string $attributes
	 *
	 * @return array
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	protected function _get_attributes($attributes){
		$attr = [];
		$preg_error = PREG_NO_ERROR;
		$pattern = '#(?<name>^|\w+)\=(\'?)(?<value>[^\']*?)\2(?: |$)#';

		if(preg_match_all($pattern, $attributes, $matches, PREG_SET_ORDER) > 0){
			foreach($matches as $attribute){
				$name = empty($attribute['name']) ? $this->options->bbtag_placeholder : strtolower(trim($attribute['name']));

				$value = trim($attribute['value']);
				$value = $this->_base_module->sanitize($value);

				$attr[$name] = $value;
			}
		}

		if($preg_error !== PREG_NO_ERROR){
			throw new BBCodeException('preg_match_all() died due to a '.$this->preg_error[$preg_error]
					.' ('.$preg_error.')'.PHP_EOL.htmlspecialchars(print_r($attributes, true)));
		}

		return $attr;
	}

}
