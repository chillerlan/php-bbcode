<?php
/**
 * Class Expanders
 *
 * @filesource   Expanders.php
 * @created      24.04.2018
 * @package      chillerlan\BBCode\Output\HTML
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\HTML;

class Expanders extends HTMLModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['expander', 'quote', 'spoiler', 'cw'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		$id = $this->randomID();

		return '<div class="'.$this->tag.'-container">'.
		       '<div data-id="'.$id.'" class="'.$this->tag.'-header expander"><span>'.$this->tag.': '.$this->getAttribute('desc').'</span></div>'. // @todo: desc in tag attribute
		       '<div id="'.$id.'" class="'.$this->tag.'-body" style="display:none;">'.$this->content.'</div>'.
		       '</div>';
	}

	/**
	 * @return string
	 */
	protected function quote():string{

		if(empty($this->content)){
			return '';
		}

		$id  = $this->randomID();
		$url = filter_var($this->getAttribute('url'), FILTER_VALIDATE_URL);

		// @todo
		return '<div class="quote-container">'.
		       '<div data-id="'.$id.'" class="quote-header expander">quote '.($this->getAttribute('source', null) ?? '').(!empty($url) ? ' <small>[<a href="'.$url.'">link</a>]<small>' : '').'</div>'.
		       '<blockquote id="'.$id.'" class="quote-body" style="display:block;">'.$this->content.'</blockquote>'. // @todo: collapse (js: collapse child elements etc.)
		       '</div>';
	}

}
