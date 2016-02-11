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

use chillerlan\bbcode\Modules\BaseModuleInterface;
use chillerlan\bbcode\Modules\ModuleInterface;
use ReflectionClass;

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
	 * Holds an array of singletags
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = [];

	/**
	 * Holds an array of allowed tags
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$allowed_tags
	 */
	protected $allowed_tags = [];

	/**
	 * Holds an array of encoder module instances
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [];

	/**
	 * testing...
	 *
	 * @link https://github.com/chillerlan/bbcode/issues/1
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
	 * Holds the parser options
	 *
	 * @var \chillerlan\bbcode\ParserOptions
	 * @see \chillerlan\bbcode\Modules\Tagmap::$options
	 */
	protected $parserOptions;

	/**
	 * Holds the parser extension instance
	 *
	 * @var \chillerlan\bbcode\ParserExtensionInterface
	 */
	protected $parserExtensionInterface;

	/**
	 * Holds the base module instance
	 *
	 * @var \chillerlan\bbcode\Modules\BaseModuleInterface
	 */
	protected $baseModuleInterface;

	/**
	 * Holds the current encoder module
	 *
	 * @var \chillerlan\bbcode\Modules\ModuleInterface
	 */
	protected $module;

	/**
	 * Holds a BBTemp instance
	 *
	 * @var \chillerlan\bbcode\BBTemp
	 */
	protected $BBTemp;

	/**
	 * Constructor.
	 *
	 * @param \chillerlan\bbcode\ParserOptions $options [optional]
	 */
	public function __construct(ParserOptions $options = null){
		$this->setOptions(!$options ? new ParserOptions : $options);
		$this->BBTemp = new BBTemp;
	}


	/**
	 * A simple class loader
	 *
	 * @param string $class     FQCN
	 * @param string $interface FQCN
	 *
	 * @param mixed  $params    [optional] the following arguments are optional and will be passed to the class constructor if present.
	 *
	 * @link https://github.com/chillerlan/framework/blob/master/src/Core/Traits/ClassLoaderTrait.php
	 *
	 * @return object of type $interface
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	private function __loadClass($class, $interface, ...$params){ // phpDocumentor stumbles across the ... syntax
		if(class_exists($class)){
			$reflectionClass = new ReflectionClass($class);

			if(!$reflectionClass->implementsInterface($interface)){
				throw new BBCodeException($class.' does not implement '.$interface);
			}

			return $reflectionClass->newInstanceArgs($params);
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
	public function setOptions(ParserOptions $options){
		$this->parserOptions = $options;
		$this->baseModuleInterface = $this->__loadClass($this->parserOptions->base_module, BaseModuleInterface::class);

		if($this->parserOptions->parser_extension){
			$this->parserExtensionInterface =
				$this->__loadClass($this->parserOptions->parser_extension, ParserExtensionInterface::class, $this->parserOptions);
		}

		$module_info = $this->baseModuleInterface->getInfo();
		foreach($module_info->modules as $module){
			$this->module = $this->__loadClass($module, ModuleInterface::class);

			$tagmap = $this->module->getTags();
			foreach($tagmap->tags as $tag){
				$this->tagmap[$tag] = $module;
			}

			$this->modules[$module] = $this->module;
			$this->noparse_tags     = array_merge($this->noparse_tags, $tagmap->noparse_tags);
			$this->singletags       = array_merge($this->singletags, $tagmap->singletags);
		}

		$this->parserOptions->eol_token  = $module_info->eol_token;
		$this->parserOptions->singletags = implode('|', $this->singletags);

		if(is_array($this->parserOptions->allowed_tags) && !empty($this->parserOptions->allowed_tags)){
			foreach($this->parserOptions->allowed_tags as $tag){
				if(array_key_exists($tag, $this->tagmap)){
					$this->allowed_tags[] = $tag;
				}
			}
		}
		else{
			if($this->parserOptions->allow_all){
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
	 * Returns the singletags
	 *
	 * @return array
	 */
	public function getSingle(){
		sort($this->singletags);
		return $this->singletags;
	}

	/**
	 * Encodes a BBCode string to HTML (or whatevs)
	 *
	 * @param string $bbcode
	 *
	 * @return string
	 */
	public function parse($bbcode){
		if($this->parserOptions->sanitize){
			$bbcode = $this->baseModuleInterface->sanitize($bbcode);
		}

		$bbcode = $this->parserExtensionInterface->pre($bbcode);
		// change/move potentially closed singletags before -> base module
		$this->bbcode_pre = $bbcode;
		$bbcode = preg_replace('#\[('.$this->parserOptions->singletags.')((?:\s|=)[^]]*)?]#is', '[$1$2][/$1]', $bbcode);
		$bbcode = str_replace(["\r", "\n"], ['', $this->parserOptions->eol_placeholder], $bbcode);
		$bbcode = $this->__parse($bbcode);
		$bbcode = $this->parserExtensionInterface->post($bbcode);
		$bbcode = str_replace($this->parserOptions->eol_placeholder, $this->parserOptions->eol_token, $bbcode);

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
	protected function __parse($bbcode){
		static $callback_count = 0;
		$callback = false;
		$preg_error = PREG_NO_ERROR;

		if(is_array($bbcode) && isset($bbcode['tag'], $bbcode['attributes'], $bbcode['content'])){
			$tag = strtolower($bbcode['tag']);
			$attributes = $this->getAttributes($bbcode['attributes']);
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

		if($callback_count < (int)$this->parserOptions->nesting_limit && !in_array($tag, $this->noparse_tags)){
			$pattern = '#\[(?<tag>\w+)(?<attributes>(?:\s|=)[^]]*)?](?<content>(?:[^[]|\[(?!/?\1((?:\s|=)[^]]*)?])|(?R))*)\[/\1]#';
			$content = preg_replace_callback($pattern, __METHOD__, $content);
			$preg_error = preg_last_error();
		}

		// still testing...
		if($preg_error !== PREG_NO_ERROR){
			// @codeCoverageIgnoreStart
			throw new BBCodeException('preg_replace_callback() died on ['.$tag.'] due to a '.$this->preg_error[$preg_error]
					.' ('.$preg_error.')'.PHP_EOL.htmlspecialchars(print_r($bbcode, true)));
			// @codeCoverageIgnoreEnd
		}

		if($callback && isset($this->tagmap[$tag]) && in_array($tag, $this->allowed_tags)){
			$this->BBTemp->tag        = $tag;
			$this->BBTemp->attributes = $attributes;
			$this->BBTemp->content    = $content;
			$this->BBTemp->options    = $this->parserOptions;
			$this->BBTemp->depth      = $callback_count;

			$this->module = $this->modules[$this->tagmap[$tag]];
			$this->module->setBBTemp($this->BBTemp);
			$content = $this->module->transform();
		}

		$callback_count = 0;

		return $content;
	}

	/**
	 * The attributes parser
	 *
	 * @param string $attributes
	 *
	 * @return array
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	protected function getAttributes($attributes){
		$attr = [];
		$pattern = '#(?<name>^|\w+)\=(\'?)(?<value>[^\']*?)\2(?: |$)#';

		if(preg_match_all($pattern, $attributes, $matches, PREG_SET_ORDER) > 0){
			foreach($matches as $attribute){
				$name = empty($attribute['name']) ? $this->parserOptions->bbtag_placeholder : strtolower(trim($attribute['name']));

				$value = trim($attribute['value']);
				$value = $this->baseModuleInterface->sanitize($value);

				$attr[$name] = $value;
			}
		}

		$preg_error = preg_last_error();

		if($preg_error !== PREG_NO_ERROR){
			// @codeCoverageIgnoreStart
			throw new BBCodeException('preg_match_all() died due to a '.$this->preg_error[$preg_error]
					.' ('.$preg_error.')'.PHP_EOL.htmlspecialchars(print_r($attributes, true)));
			// @codeCoverageIgnoreEnd
		}

		return $attr;
	}

}
