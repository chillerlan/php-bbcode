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

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms image tags into HTML5
 */
class Images extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['img'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){

		if(!$url = $this->checkUrl($this->content)){
			return '';
		}

		$alt = $this->getAttribute('alt');

		return '<img src="'.$url.'" '
			.($alt ? 'alt="'.$alt.'"' : 'alt')
			.$this->getTitle()
			.$this->getCssClass(['bb-image']).' />';
	}

}
