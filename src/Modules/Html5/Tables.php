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
 * Tables, as HTML5 as possible...
 *
 * @link http://www.w3.org/TR/html5/tabular-data.html
 */
class Tables extends Html5BaseModule implements ModuleInterface{

	/**
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
	public function _transform(){
		switch(true){
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
		if(empty($this->content)){
			return '';
		}

		$this->_style = [
			'width' => $this->get_attribute('width'),
		];

		return '<table'.$this->get_css_class('bb-table').$this->get_style().'>'.$this->eol($this->content).'</table>';
	}

	/**
	 * @return string
	 */
	private function col(){
		$span = $this->get_attribute('span');

		return '<col'.($span ? ' span="'.intval($span).'"' : '').$this->get_css_class().' />';
	}

	/**
	 * @return string
	 */
	private function colgroup(){
		$span = $this->get_attribute('span');

		return '<colgroup'.($span ? ' span="'.intval($span).'"' : '').$this->get_css_class().'>'.$this->eol($this->content).'</colgroup>';
	}

	/**
	 * @return string
	 */
	private function caption(){
		return '<caption>'.$this->eol($this->content, $this->eol_token).'</caption>';
	}

	/**
	 * @return string
	 */
	private function rows(){
		return '<'.$this->tag.'>'.$this->eol($this->content).'</'.$this->tag.'>';
	}

	/**
	 * @return string
	 */
	private function cells(){

		switch(true){
			case $align = $this->get_attribute('align'):
				$this->_style['text-align'] = in_array($align, $this->_text_align) ? $align : 'inherit';
				break;
			case $valign = $this->get_attribute('valign'):
				$this->_style['vertical-align'] = in_array($valign, ['baseline', 'bottom', 'middle', 'top']) ? $valign : 'inherit';
				break;
			case $width = $this->get_attribute('width'):
				$this->_style['width'] = $width;
				break;
			case $this->get_attribute('nowrap'):
				$this->_style['white-space'] = 'nowrap';
				break;
		}

		$span = '';
		foreach(['colspan', 'rowspan'] as $s){
			$_span = $this->get_attribute($s);
			$span .=  $_span ? ' '.$s.'="'.intval($_span).'"' : '';
		}

		$abbr  = '';
		if($this->tag === 'th'){
			$_abbr = $this->get_attribute('abbr');
			$abbr = $_abbr ? ' abbr="'.$_abbr.'"' : '';
		}

		return '<'.$this->tag.$span.$abbr.$this->get_style().'>'.$this->eol($this->content, $this->eol_token).'</'.$this->tag.'>';
	}

}
