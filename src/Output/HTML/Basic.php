<?php
/**
 * Class Basic
 *
 * @filesource   Basic.php
 * @created      26.04.2018
 * @package      chillerlan\BBCode\Output\HTML
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\HTML;

class Basic extends HTMLModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = [
		'noparse', 'nobb', 'color', 'font', 'size','br', 'hr', 'clear','img', 'url', 'c', 'list',
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
		'strong', 'sub', 'sup', 'del', 'small', 'em', 's', 'b', 'u', 'i', 'tt',
	];

	/**
	 * @var array
	 */
	protected $singletags = ['br', 'hr', 'clear'];

	/**
	 * @var array
	 */
	protected $noparse = ['noparse', 'nobb', 'c'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		return '<'.$this->tag.' class="bb-text '.$this->tag.'">'.$this->content.'</'.$this->tag.'>';
	}

	/**
	 * @return string
	 */
	protected function nobb():string{
		return $this->noparse();
	}

	/**
	 * @return string
	 */
	protected function noparse():string{
		$this->clearPseudoClosingTags();

		return '<pre class="bb-noparse">'.$this->content.'</pre>'; // $this->match
	}

	/**
	 * @return string
	 */
	protected function c(){ // @todo
		$this->clearPseudoClosingTags();
		return '<code class="bb-inline-code" style="display: inline">'.$this->content.'</code>';
	}

	/**
	 * @return string
	 */
	protected function color(){
		// @todo: preg_match('/^#([a-f\d]{3}){1,2}$/i', $value)
		return '<span class="bb-text color" style="color: '.'; background-color: '.';">'.$this->content.'</span>';
	}

	/**
	 * @return string
	 */
	protected function font(){ // @todo: restrict fonts via classname
		return '<span class="bb-text font comic-sans">'.$this->content.'</span>';
	}

	/**
	 * @return string
	 */
	protected function size(){ // @todo: restrict sizes via css
		return '<span class="bb-text size extra-tiny">'.$this->content.'</span>';
	}

	/**
	 * @return string
	 */
	public function br():string{ // @todo: adjust padding-bottom
		return '<br class="bb-br"/>';
	}

	/**
	 * @return string
	 */
	public function hr():string{ // @todo line style
		return '<hr class="bb-hr"/>';
	}

	/**
	 * @return string
	 */
	protected function clear(){
		return '<br class="bb-clear '.$this->bbtagIn(['both', 'left', 'right'], 'both').'"/>';
	}

	/**
	 * @return string
	 */
	protected function img():string{
		$url = filter_var($this->content, FILTER_VALIDATE_URL);// @todo

		if(!$url){
			return '';
		}

		return '<img src="'.$url.'" class="bb-img" alt />'; // @todo: alt, title
	}

	/**
	 * @return string
	 */
	protected function url():string{

		if(empty($this->content)){
			return '';
		}

		$url    = filter_var($this->bbtag() ?? $this->content, FILTER_VALIDATE_URL);// @todo
		$host   = parse_url($url, PHP_URL_HOST);
		$target = (!empty($host) && (isset($_SERVER['SERVER_NAME']) && $host === $_SERVER['SERVER_NAME'])) || empty($host) ? 'self' : 'blank';

		return '<a class="bb-url '.$target.'" '.($url ? ' href="'.$url.'" target="_'.$target.'"' : '').'>'.$this->content.'</a>'; // .$this->getTitle()

	}

	/**
	 * @return string
	 */
	protected function list():string{

		if(empty($this->content)){
			return '';
		}

		$ol    = ['0', '1', 'a', 'A', 'i', 'I'];
		$ul    = ['c', 'd', 's'];
		$types = [
			'0' => 'decimal-leading-zero',
			'1' => 'decimal',
			'a' => 'lower-alpha',
			'A' => 'upper-alpha',
			'i' => 'lower-roman',
			'I' => 'upper-roman',
			'c' => 'circle',
			's' => 'square',
			'd' => 'disc',
		];

		$start    = $this->bbtag();
		$list_tag = (count($this->attributes) === 0 || $this->attributeIn('type', $ul) ? 'ul' : 'ol');

		return '<'.$list_tag.' class="bb-list '.$this->attributeKeyIn('type', $types, 'disc').'" '
		       .(is_numeric($start) && $this->attributeIn('type', $ol) ? ' start="'.ceil($start).'"' : '')
		       .($this->getAttribute('reversed') && $this->attributeIn('type', $ol) ? ' reversed="true"' : '')
		       .'>'
		       .'<li>'.implode(array_slice(explode('[*]', $this->content), true), '</li><li>').'</li>' // nasty
		       .'</'.$list_tag.'>';
	}

}
