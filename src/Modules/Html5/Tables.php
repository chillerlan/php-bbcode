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

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms table tags into HTML5, as HTML5 as possible...
 *
 * @link http://www.w3.org/TR/html5/tabular-data.html
 */
class Tables extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['col', 'colgroup', 'caption', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th'];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "single tag"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = ['col'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){
		$this->_style = [];

		switch(true){
			case $this->tagIn(['tr', 'thead', 'tbody', 'tfoot']):
				return $this->rows();
			case $this->tagIn(['td', 'th']):
				return $this->cells();
			default:
				return call_user_func([$this, $this->tag]);
		}
	}

	/**
	 * Processes [table]
	 *
	 * @return string
	 */
	private function table(){
		if(empty($this->content)){
			return '';
		}

		$this->_style = [
			'width' => $this->getAttribute('width'),
		];

		return '<table'.$this->get_css_class('bb-table').$this->get_style().'>'.$this->eol($this->content).'</table>';
	}

	/**
	 * Processes [col]
	 *
	 * @return string
	 */
	private function col(){
		$span = $this->getAttribute('span');

		return '<col'.($span ? ' span="'.intval($span).'"' : '').$this->get_css_class().' />';
	}

	/**
	 * Processes [colgroup]
	 *
	 * @return string
	 */
	private function colgroup(){
		$span = $this->getAttribute('span');

		return '<colgroup'.($span ? ' span="'.intval($span).'"' : '').$this->get_css_class().'>'.$this->eol($this->content).'</colgroup>';
	}

	/**
	 * Processes [caption]
	 *
	 * @return string
	 */
	private function caption(){
		return '<caption>'.$this->eol($this->content, $this->eol_token).'</caption>';
	}

	/**
	 * Processes [tr|thead|tbody|tfoot]
	 *
	 * @return string
	 */
	private function rows(){
		return '<'.$this->tag.'>'.$this->eol($this->content).'</'.$this->tag.'>';
	}

	/**
	 * Processes [td|th]
	 *
	 * @return string
	 */
	private function cells(){

		$align = $this->getAttribute('align');
		if($align && in_array($align, $this->_text_align)){
			$this->_style['text-align'] = $align;
		}

		$valign = $this->getAttribute('valign');
		if($valign && in_array($valign, ['baseline', 'bottom', 'middle', 'top'])){
			$this->_style['vertical-align'] = $valign;
		}

		if($width = $this->getAttribute('width')){
			$this->_style['width'] = $width;
		}

		if($this->getAttribute('nowrap')){
			$this->_style['white-space'] = 'nowrap';
		}

		$span = '';
		foreach(['colspan', 'rowspan'] as $s){
			$_span = $this->getAttribute($s);
			$span .=  $_span ? ' '.$s.'="'.intval($_span).'"' : '';
		}

		$abbr  = '';
		if($this->tag === 'th'){
			$_abbr = $this->getAttribute('abbr');
			$abbr = $_abbr ? ' abbr="'.$_abbr.'"' : '';
		}

		return '<'.$this->tag.$span.$abbr.$this->get_style().'>'.$this->eol($this->content, $this->eol_token).'</'.$this->tag.'>';
	}

}
