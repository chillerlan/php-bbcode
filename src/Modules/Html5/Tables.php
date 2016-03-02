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
 * @todo
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
	public function __transform():string{

		switch(true){
			case $this->tag === 'caption' && !empty($this->content):
				return $this->caption();
			case $this->tag === 'col':
				return $this->col();
			case $this->tag === 'colgroup' && !empty($this->content):
				return $this->colgroup();
			case $this->tag === 'table' && !empty($this->content):
				return $this->table();
			case ($this->tagIn(['thead', 'tbody', 'tfoot']) && !empty($this->content)) || $this->tag === 'tr':
				return $this->rows();
			case $this->tagIn(['td', 'th']):
				return $this->cells();
			default:
				return '';
		}

	}

	/**
	 * Processes [table]
	 *
	 * @return string
	 */
	protected function table():string{

		return '<table'.$this->getCssClass(['bb-table'])
			.$this->getStyle(['width' => $this->getAttribute('width')])
			.'>'.$this->eol($this->content).'</table>';
	}

	/**
	 * Processes [col]
	 *
	 * @return string
	 */
	protected function col():string{
		$span = $this->getAttribute('span');

		return '<col'.($span ? ' span="'.intval($span).'"' : '').$this->getCssClass().' />';
	}

	/**
	 * Processes [colgroup]
	 *
	 * @return string
	 */
	protected function colgroup():string{
		$span = $this->getAttribute('span');

		return '<colgroup'.($span ? ' span="'.intval($span).'"' : '').$this->getCssClass().'>'.$this->eol($this->content).'</colgroup>';
	}

	/**
	 * Processes [caption]
	 *
	 * @return string
	 */
	protected function caption():string{
		return '<caption>'.$this->eol($this->content, $this->eol_token).'</caption>';
	}

	/**
	 * Processes [tr|thead|tbody|tfoot]
	 *
	 * @return string
	 */
	protected function rows():string{
		return '<'.$this->tag.'>'.$this->eol($this->content).'</'.$this->tag.'>';
	}

	/**
	 * Processes [td|th]
	 *
	 * @return string
	 */
	protected function cells():string{
		$style = [];
		$align = $this->getAttribute('align');

		if($align && in_array($align, self::TEXT_ALIGN)){
			$style['text-align'] = $align;
		}

		$valign = $this->getAttribute('valign');
		if($valign && in_array($valign, ['baseline', 'bottom', 'middle', 'top'])){
			$style['vertical-align'] = $valign;
		}

		if($width = $this->getAttribute('width')){
			$style['width'] = $width;
		}

		if($this->getAttribute('nowrap')){
			$style['white-space'] = 'nowrap';
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

		return '<'.$this->tag.$span.$abbr.$this->getStyle($style).'>'.$this->eol($this->content, $this->eol_token).'</'.$this->tag.'>';
	}

}
