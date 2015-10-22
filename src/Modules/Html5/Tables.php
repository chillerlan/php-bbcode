<?php
/**
 * Class Tables
 *
 * @filesource   Tables.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;

/**
 * @todo work in progress, strange bug with singletag regex and [col]
 * @link http://www.w3.org/TR/html5/tabular-data.html
 */
class Tables extends Html5BaseModule implements ModuleInterface{

	/**
	 *
	 * @var array
	 */
	protected $tags = ['col', 'colgroup', 'caption', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th'];

	/**
	 * @var array
	 */
	protected $singletags = ['col'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function transform(){
		$this->check_tag();

		switch(true){
			case 'co':
				$span = $this->get_attribute('span');
				$span = $span ? ' span="'.$span.'"' : '';
				return '<col'.$span.$this->get_css_class().' />';
			case $this->tag_in(['colgroup']):
				return $this->colgroup();
			case $this->tag_in(['tr', 'thead', 'tbody', 'tfoot']):
				return $this->rows();
			case $this->tag_in(['td', 'th']):
				return $this->cells();
			default:
				return call_user_func([$this, $this->tag]);
		}
	}

	/**
	 * @return string
	 */
	private function table(){



		return '<table class="bb-table" style="'.(isset($this->attributes['width']) ? 'width:'.$this->attributes['width'].';' : '').'">'.$this->content.'</table>';

	}

	/**
	 * @return string
	 */
	private function caption(){

		return '<caption>'.$this->content.'</caption>';

	}

	/**
	 * @return string
	 */
	private function colgroup(){
		return '<'.$this->tag.$this->get_attribute('span', '').$this->get_css_class().($this->tag === 'colgroup' ? '></colgroup>' : ' />');
	}


	/**
	 * @return string
	 */
	private function rows(){
		// attribute align is invalid for XHTML1.1 and HTML5 - solved by css
		return str_replace(["\r", "\n"], '', '<'.$this->tag.' style="'.
				(isset($this->attributes['align']) && in_array(strtolower($this->attributes['align']), ['left', 'right', 'center']) ? 'text-align:'.$this->attributes['align'].';' : 'text-align:left;').
				'">'.$this->content.'</'.$this->tag.'>');
	}

	/**
	 * @return string
	 */
	private function cells(){
		// todo: valign
		// attribute align is invalid for XHTML1.1 and HTML5 - solved by css
		// nowrap and width attributes are invalid for xhtml1.1 - solved as css
		return '<'.$this->tag.
		(isset($this->attributes['colspan']) && is_numeric($this->attributes['colspan']) ? ' colspan="'.ceil($this->attributes['colspan']).'"' : '').' style="'.
		(array_key_exists('nowrap', $this->attributes) ? 'white-space:nowrap;' : '').
		(isset($this->attributes['width']) ? 'width:'.$this->attributes['width'].';' : '').
		(isset($this->attributes['align']) && in_array(strtolower($this->attributes['align']), ['left', 'right', 'center']) ? 'text-align:'.$this->attributes['align'].';' : 'text-align:left;').
		'">'.nl2br($this->content).'</'.$this->tag.'>'; //,1
	}

}
