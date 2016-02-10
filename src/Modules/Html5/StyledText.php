<?php
/**
 * Class StyledText
 *
 * @filesource   StyledText.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms several styled text tags into HTML5
 */
class StyledText extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['s', 'b', 'u', 'i', 'tt', 'size', 'color', 'font'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){
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
