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
	 * CSS classes for each tag
	 */
	const CSS_CLASS = [
		'color' => 'color',
		'font'  => 'font',
		'size'  => 'size',
		'tt'    => 'typewriter',
		'i'     => 'italic',
		'b'     => 'bold',
		's'     => 'linethrough',
		'u'     => 'underline',
	];

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

		if(in_array($this->tag, ['color', 'font', 'size'])){
			$bbtag = $this->bbtag();

			$this->_style = [
				'color' => ['color' => $bbtag],
				'font'  => ['font-family' => $this->bbtag_in($this->_allowed_fonts, '')],
				'size'  => ['font-size' => $bbtag],
			][$this->tag];
		}

		return '<span'.$this->get_title()
			.$this->get_css_class(['bb-text', self::CSS_CLASS[$this->tag]])
			.$this->get_style().'>'.$this->content.'</span>';
	}

}
