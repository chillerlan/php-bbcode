<?php
/**
 * Class Image
 *
 * @filesource   Images.php
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
class Images extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 * @todo gallery?
	 */
	protected $tags = ['img'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function _transform(){

		if(!$url = $this->check_url($this->content)){
			return '';
		}

		$alt = $this->get_attribute('alt');

		return '<img src="'.$url.'" '.($alt ? 'alt="'.$alt.'"' : 'alt').$this->get_title().$this->get_css_class('bb-image').' />';
	}

}
