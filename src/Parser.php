<?php
/**
 * Class Parser
 *
 * @version      1.1.0
 * @date         11.02.2016
 *
 * @filesource   Parser.php
 * @created      18.09.2015
 * @package      chillerlan\bbcode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode;

use chillerlan\bbcode\Language\LanguageInterface;
use chillerlan\bbcode\Modules\{BaseModuleInterface, ModuleInterface};
use chillerlan\bbcode\Traits\ClassLoaderTrait;

/**
 * Regexp BBCode parser
 *
 * idea and regexes from developers-guide.net years ago
 *
 * @link http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html
 */
class Parser{
	use ClassLoaderTrait;

	/**
	 * testing...
	 *
	 * @link https://github.com/chillerlan/bbcode/issues/1
	 * @var array
	 */
	const PREG_ERROR = [
		PREG_INTERNAL_ERROR        => 'PREG_INTERNAL_ERROR',
		PREG_BACKTRACK_LIMIT_ERROR => 'PREG_BACKTRACK_LIMIT_ERROR',
		PREG_RECURSION_LIMIT_ERROR => 'PREG_RECURSION_LIMIT_ERROR',
		PREG_BAD_UTF8_ERROR        => 'PREG_BAD_UTF8_ERROR',
		PREG_BAD_UTF8_OFFSET_ERROR => 'PREG_BAD_UTF8_OFFSET_ERROR',
		6                          => 'PREG_JIT_STACKLIMIT_ERROR', // int key to prevent a notice in php 5
	];

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
	protected $moduleInterface;

	/**
	 * Holds a BBTemp instance
	 *
	 * @var \chillerlan\bbcode\BBTemp
	 */
	protected $BBTemp;

	/**
	 * Holds the translation class for the current language
	 *
	 * @var \chillerlan\bbcode\Language\LanguageInterface
	 */
	protected $languageInterface;

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
	 * Sets the parser options
	 *
	 * @param \chillerlan\bbcode\ParserOptions $options
	 *
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function setOptions(ParserOptions $options){
		$this->parserOptions       = $options;
		$this->baseModuleInterface = $this->__loadClass($this->parserOptions->baseModuleInterface, BaseModuleInterface::class);
		$this->languageInterface   = $this->__loadClass($this->parserOptions->languageInterface, LanguageInterface::class);

		if($this->parserOptions->parserExtensionInterface){
			$this->parserExtensionInterface =
				$this->__loadClass($this->parserOptions->parserExtensionInterface, ParserExtensionInterface::class, $this->parserOptions);
		}

		$module_info = $this->baseModuleInterface->getInfo();
		foreach($module_info->modules as $module){
			$this->moduleInterface = $this->__loadClass($module, ModuleInterface::class);

			$tagmap = $this->moduleInterface->getTags();
			foreach($tagmap->tags as $tag){
				$this->tagmap[$tag] = $module;
			}

			$this->modules[$module] = $this->moduleInterface;
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
	 */
	public function getTagmap():array{
		ksort($this->tagmap);
		return $this->tagmap;
	}

	/**
	 * Returns the currently allowed tags
	 */
	public function getAllowed():array{
		sort($this->allowed_tags);
		return $this->allowed_tags;
	}

	/**
	 * Returns the noparse tags
	 */
	public function getNoparse():array{
		sort($this->noparse_tags);
		return $this->noparse_tags;
	}

	/**
	 * Returns the singletags
	 */
	public function getSingle():array{
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
	public function parse(string $bbcode):string{
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
	protected function __parse($bbcode):string{
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
			$message = sprintf($this->languageInterface->parserExceptionCallback(), $tag, self::PREG_ERROR[$preg_error], $preg_error);
			throw new BBCodeException($message);
			// @codeCoverageIgnoreEnd
		}

		if($callback && isset($this->tagmap[$tag]) && in_array($tag, $this->allowed_tags)){
			$this->BBTemp->tag               = $tag;
			$this->BBTemp->attributes        = $attributes;
			$this->BBTemp->content           = $content;
			$this->BBTemp->parserOptions     = $this->parserOptions;
			$this->BBTemp->languageInterface = $this->languageInterface;
			$this->BBTemp->depth             = $callback_count;

			$this->moduleInterface = $this->modules[$this->tagmap[$tag]];
			$this->moduleInterface->setBBTemp($this->BBTemp);
			$content = $this->moduleInterface->transform();
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
	protected function getAttributes(string $attributes):array{
		$attr    = [];
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
			$message = sprintf($this->languageInterface->parserExceptionMatchall(), self::PREG_ERROR[$preg_error], $preg_error);
			throw new BBCodeException($message);
			// @codeCoverageIgnoreEnd
		}

		return $attr;
	}

}
