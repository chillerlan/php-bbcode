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

	protected $tags = ['br', 'hr', 'clear'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function transform(){
		$this->check_tag();

		switch($this->tag){
			case 'clear':
				return '<br style="clear:both;" />';
			default:
				return '<'.$this->tag.$this->get_css_class().' />';
		}
	}

}
