<?php
/**
 * Class Simpletext
 *
 * @filesource   Simpletext.php
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
 * Transforms several simple text tags into HTML5
 */
class Simpletext extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'strong', 'sub', 'sup', 'del', 'small'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function _transform(){
		if(empty($this->content)){
			return '';
		}

		return '<'.$this->tag.$this->get_css_class().'>'.$this->content.'</'.$this->tag.'>';
	}

}
