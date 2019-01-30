<?php
/**
 * Class Tables
 *
 * @filesource   Tables.php
 * @created      24.04.2018
 * @package      chillerlan\BBCode\Output\HTML
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\HTML;

class Tables extends HTMLModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['col', 'colgroup', 'caption', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th'];

	/**
	 * @var array
	 */
	protected $singletags = ['col'];

	/**
	 * @return string
	 */
	public function transform():string{

		if(in_array($this->tag, ['td', 'th'], true)){
			return $this->__cells();
		}

		if(in_array($this->tag, ['thead', 'tbody', 'tfoot'], true)){
			return $this->__body();
		}

		return '';
	}

	/**
	 * @return string
	 */
	protected function table():string{

		if(empty($this->content)){
			return '';
		}

		// @todo preg_match('/^[\d\.]+(px|pt|em|%)$/', $value):
		return '<table class="bb-table" style="width:'.$this->getAttribute('width', '100%').';">'.$this->eol($this->content).'</table>';
	}

	/**
	 * @return string
	 */
	protected function col():string{
		$span = $this->getAttribute('span');

		return '<col'.($span ? ' span="'.intval($span).'"' : '').' />';
	}

	/**
	 * @return string
	 */
	protected function colgroup():string{

		if(empty($this->content)){
			return '';
		}

		$span = $this->getAttribute('span');

		return '<colgroup'.($span ? ' span="'.intval($span).'"' : '').'>'.$this->eol($this->content).'</colgroup>';
	}

	/**
	 * @return string
	 */
	protected function caption():string{

		if(empty($this->content)){
			return '';
		}

		return '<caption>'.$this->eol($this->content, '<br/>').'</caption>';
	}

	/**
	 * @return string
	 */
	protected function tr():string{
		return '<tr>'.$this->eol($this->content).'</tr>';
	}

	/**
	 * @return string
	 */
	protected function __body():string{
		return !empty($this->content) ? '<'.$this->tag.'>'.$this->eol($this->content).'</'.$this->tag.'>' : '';
	}

	/**
	 * Processes [td|th]
	 *
	 * @return string
	 */
	protected function __cells():string{
		$abbr    = $this->getAttribute('abbr');
		$colspan = $this->getAttribute('colspan');
		$rowspan = $this->getAttribute('rowspan');

		if($colspan){
			$colspan = ' colspan="'.intval($colspan).'"';
		}

		if($rowspan){
			$rowspan = ' rowspan="'.intval($rowspan).'"';
		}

		if($this->tag === 'th' && $abbr){
			$abbr = ' abbr="'.$abbr.'"';
		}

		return '<'.$this->tag.$colspan.$rowspan.$abbr.$this->__getCellStyle().'>'.$this->eol($this->content, '<br/>').'</'.$this->tag.'>';
	}

	/**
	 * @return string
	 */
	protected function __getCellStyle():string{
		$align  = $this->getAttribute('align');
		$valign = $this->getAttribute('valign');
		$width  = $this->getAttribute('width');
		$style  = [];

		switch(true){
			case $align && in_array($align, ['left', 'center', 'right', 'justify', 'start', 'end', 'inherit',]):
				$style['text-align'] = $align;
				break;
			case $valign && in_array($valign, ['baseline', 'bottom', 'middle', 'top']):
				$style['vertical-align'] = $valign;
				break;
			case $width:
				// @todo preg_match('/^[\d\.]+(px|pt|em|%)$/', $value):
				$style['width'] = $width;
				break;
			case $this->getAttribute('nowrap'):
				$style['white-space'] = 'nowrap';
				break;
		}

		return !empty($style) ? ' style="'.implode(';', $style).'"' : '';
	}

}
