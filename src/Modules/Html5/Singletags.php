<?php
/**
 * Class Singletags
 *
 * @filesource   Singletags.php
 * @created      19.09.2015
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
 * Processes single tag bbcodes
 */
class Singletags extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 */
	protected $tags = ['br', 'clear', 'hr'];

	/**
	 * @var array
	 */
	protected $singletags = ['br', 'clear', 'hr'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function transform(){
		$this->check_tag();

		switch($this->tag){
			case 'clear':
				$this->_style = ['clear' => $this->bbtag_in(['both', 'left', 'right'], 'both')];
				return '<br'.$this->get_style().' />';
			default:
				return '<'.$this->tag.$this->get_css_class().' />';
		}
	}

}
