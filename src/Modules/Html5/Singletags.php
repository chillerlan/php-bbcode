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

use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;

/**
 * Transforms several single tags into HTML5
 */
class Singletags extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['br', 'clear', 'hr'];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "single tag"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = ['br', 'clear', 'hr'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function _transform(){

		switch($this->tag){
			case 'clear':
				$this->_style = ['clear' => $this->bbtag_in(['both', 'left', 'right'], 'both')];
				return '<br'.$this->get_style().' />';
			default:
				return '<'.$this->tag.$this->get_css_class().' />';
		}
	}

}
