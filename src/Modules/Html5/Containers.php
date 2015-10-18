<?php
/**
 * Class Containers
 *
 * @filesource   Containers.php
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
class Containers extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 * @todo: flex, inline?
	 */
	protected $tags = ['p', 'div', 'left', 'right', 'center'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function transform(){
		$this->check_tag();

		$tag = $this->tag_in(['p', 'div'], 'p');

		$_align = ['left', 'right', 'center'];
		$align = $this->tag_in($_align, '');
		if(!$align){
			$align = $this->attribute_in('align', $_align, 'left');
		}

		$this->_style = ['text-align' => $align];

		return '<'.$tag.$this->get_title().$this->get_css_class().$this->get_style().'>'.$this->content.'</'.$tag.'>';
	}

}
