<?php
/**
 * Class Parser
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
use chillerlan\bbcode\ParserExtension;
use chillerlan\bbcode\ParserExtensionInterface;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 * Regexp BBCode parser
 *
 * idea and regexes from developers-guide.net years ago
 * rewritten to reduce memory consumption and limit nesting ;)
 *
 * @link http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html
 */
class Parser{

	/**
	 * map of Tag -> Module
	 *
	 * @var array
	 */
	protected $tagmap = [];

	/**
	 * @var array
	 */
	protected $noparse = [];

	/**
	 * holds an array of allowed tags
	 *
	 * @todo: allowed tags
	 *
	 * @var array
	 */
	protected $allowed = [];

	/**
	 * @var string
	 */
	protected $eol = PHP_EOL;

	/**
	 * @var bool
	 */
	protected $sanitize;

	/**
	 * @var int
	 */
	protected $nesting_limit;

	/**
	 * @var \chillerlan\bbcode\ParserExtensionInterface
	 */
	private $parser_extension;

	/**
	 * @var \chillerlan\bbcode\Modules\BaseModuleInterface
	 */
	private $base_module;

	/**
	 * holds the encoder module
	 *
	 * @var \chillerlan\bbcode\Modules\ModuleInterface
	 */
	private $module;

	/**
	 * Holds the encoder module instances
	 *
	 * @var array of ModuleInterface
	 */
	private $modules = [];

	/**
	 * @param \chillerlan\bbcode\Modules\BaseModuleInterface $base_module
	 * @param int  $nesting_limit
	 * @param bool $sanitize_html you really don't want to set it to false, do you?
	 *                            set to false in case you use something like HTML purifier or
	 *                            a non-markup output module
	 *
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function __construct(BaseModuleInterface $base_module, $nesting_limit = 100, $sanitize_html = true){
		$this->base_module = $base_module;
		$this->sanitize = (bool)$sanitize_html;
		$this->nesting_limit = (int)$nesting_limit;

		$this->parser_extension = new ParserExtension;
		$this->eol = $this->base_module->get_eol();

		foreach($this->base_module->get_modules() as $module){
			if(class_exists($module)){
				$this->module = new $module(new BBTemp);
				if($this->module instanceof ModuleInterface){
					foreach($this->module->get_tags() as $tag){
						$this->tagmap[$tag] = $module;
					}

					$this->modules[$module] = $this->module;
					$this->noparse = array_merge($this->noparse, $this->module->get_noparse_tags());
				}
				else{
					throw new BBCodeException($module.' is not of type ModuleInterface');
				}
			}
			else{
				throw new BBCodeException('class '.$module.' doesn\'t exist');
			}
		}

	}

	/**
	 * @param \chillerlan\bbcode\ParserExtensionInterface $parser_extension allows to specify a custom preparser
	 *
	 * @return $this
	 */
	public function set_parser_extension(ParserExtensionInterface $parser_extension){
		$this->parser_extension = $parser_extension;

		return $this;
	}

	/**
	 * returns the current tagmap
	 *
	 * @return array
	 */
	public function get_tagmap(){
		return $this->tagmap;
	}

	/**
	 * returns the noparse tags
	 *
	 * @return array
	 */
	public function get_noparse(){
		return $this->noparse;
	}

	/**
	 * Encodes a BBCode string to HTML (or whatevs)
	 *
	 * @param string $bbcode
	 *
	 * @return string
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function parse($bbcode){
		if($this->sanitize){
			$bbcode = $this->base_module->sanitize($bbcode);
		}

		$bbcode = $this->parser_extension->pre($bbcode);
		$bbcode = str_replace(["\r", "\n"], ['', '__BBEOL__'], $bbcode);
		$bbcode = $this->_parse($bbcode);
		$bbcode = $this->parser_extension->post($bbcode);
		$bbcode = str_replace('__BBEOL__', $this->eol, $bbcode);

		return $bbcode;
	}

	/**
	 * strng regexp bbcode killer
	 *
	 * @param string|array $bbcode BBCode as string or matches as array - callback from preg_replace_callback()
	 *
	 * @return array|string
	 */
	private function _parse($bbcode){
		static $callback_count = 0;
		$callback = false;

		if(is_array($bbcode) && isset($bbcode['tag'])){
			$tag = strtolower($bbcode['tag']);
			$attributes = $this->get_attributes($bbcode['attributes']);
			$content = $bbcode['content'];

			$callback = true;
			$callback_count++;
		}
		else{
			$tag = false;
			$attributes = [];
			$content = preg_replace('#\[(br|hr|clear)]#is', '[\1]*[/\1]', $bbcode);
		}

		if(!empty($content)){
			if($callback_count < $this->nesting_limit && !in_array($tag, $this->noparse)){ // && in_array($tag, $this->allowed)
				$pattern = '#\[(?P<tag>\w+)(?P<attributes>(?:\s|=)[^]]*)?](?P<content>(?:[^[]|\[(?!/?\1((?:\s|=)[^]]*)?])|(?R))*)\[/\1]#';
				$content = preg_replace_callback($pattern, __METHOD__, $content);
			}

			if($callback){
				if(isset($this->tagmap[$tag])){
					$bbtemp = new BBTemp;
					$bbtemp->tag = $tag;
					$bbtemp->attributes = $attributes;
					$bbtemp->content = $content;
					$bbtemp->options = [];

					$this->module = $this->modules[$this->tagmap[$tag]];
					$this->module->set_bbtemp($bbtemp);
					$content = $this->module->transform();
				}
			}
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
	 */
	private function get_attributes($attributes){
		$attr = [];
		$pattern = '#(?P<name>^|\w+)\=(\'?)(?P<value>[^\']*?)\2(?: |$)#';

		if(preg_match_all($pattern, $attributes, $matches, PREG_SET_ORDER) > 0){
			foreach($matches as $attribute){
				$name = empty($attribute['name']) ? '__BBTAG__' : strtolower(trim($attribute['name']));

				$value = trim($attribute['value']);
				$value = $this->base_module->sanitize($value);

				$attr[$name] = $value;
			}
		}

		return $attr;
	}

}
