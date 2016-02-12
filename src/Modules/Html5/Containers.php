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

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms several container tags into HTML5
 */
class Containers extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 * @todo flex, inline?
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['p', 'div', 'left', 'right', 'center'];

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

		$tag = $this->tagIn(['p', 'div'], 'p');
		$align = $this->tagIn(self::TEXT_ALIGN, '');

		if(!$align){
			$align = $this->attributeIn('align', self::TEXT_ALIGN, 'left');
		}

		return '<'.$tag.$this->getTitle().$this->getCssClass(['bb-container', $align]).'>'.$this->content.'</'.$tag.'>';
	}

}
