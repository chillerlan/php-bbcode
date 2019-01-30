<?php
/**
 * Class Basic
 *
 * @filesource   Basic.php
 * @created      26.04.2018
 * @package      chillerlan\BBCode\Output\Markdown
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\Markdown;

class Basic extends MarkdownModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['br', 'hr', 'img', 'url'];

	/**
	 * @var array
	 */
	protected $singletags = ['br', 'hr'];

	/**
	 * @return string
	 */
	protected function br():string{
		return PHP_EOL;
	}

	/**
	 * @return string
	 */
	protected function hr():string{
		return PHP_EOL.'----'.PHP_EOL;
	}

	/**
	 * @return string
	 */
	protected function img():string{ // @todo: check url, alt
		return '!['.'alt'.']('.$this->content.')';
	}

	/**
	 * @return string
	 */
	protected function url():string{ // @todo linktext

		$url = filter_var($this->bbtag() ?? $this->content, FILTER_VALIDATE_URL);// @todo

		if($url){
			return  '[url]('.$url.')';
		}

		return '';
	}
}
