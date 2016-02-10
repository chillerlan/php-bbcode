<?php
/**
 * Class Image
 *
 * @filesource   Images.php
 * @created      03.11.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markdown;

use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;

/**
 * Transforms image tags into Markdown
 */
class Images extends MarkdownBaseModule implements ModuleInterface{

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

		if(empty($this->content) || !$this->checkUrl($this->content)){
			return '';
		}

		$alt = $this->getAttribute('alt', 'image');

		return '!['.$alt.']('.$this->content.')';
	}

}
