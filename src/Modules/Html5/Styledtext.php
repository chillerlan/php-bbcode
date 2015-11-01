<?php
/**
 * Class StyledText
 *
 * @filesource   Styledtext.php
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
 *
 */
class StyledText extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 */
	protected $tags = ['s', 'b', 'u', 'i', 'tt', 'size', 'color', 'font'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function _transform(){
		if(empty($this->content)){
			return '';
		}

		$bbtag = $this->bbtag();

		$this->_style = [
			'color' => ['color' => $bbtag],
			'font'  => ['font-family' => $this->bbtag_in($this->_allowed_fonts, '')],
			'tt'    => ['font-family' => 'Courier, monospace'],
			'size'  => ['font-size' => $bbtag, 'line-height' => '1em'],
			'i'     => ['font-style' => 'italic'],
			'b'     => ['font-weight' => 'bold'],
			's'     => ['text-decoration' => 'line-through'],
			'u'     => ['text-decoration' => 'underline'],
		][$this->tag];

		return '<span'.$this->get_title().$this->get_style().'>'.$this->content.'</span>';
	}

}
