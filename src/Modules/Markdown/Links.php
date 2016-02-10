<?php
/**
 * Class Links
 *
 * @filesource   Links.php
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
 * Transforms link tags into Markdown
 */
class Links extends MarkdownBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['url', 'video', 'dmotion', 'vimeo', 'youtube', 'moddb'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){
		$url = $this->bbtag();

		if(!empty($this->content)){
			if($url && $this->checkUrl($url)){
				return '['.$this->content.']('.$url.')';
			}
			else{
				return $this->checkUrl($this->content) ? : $this->content;
			}
		}

		return $this->checkUrl($url) ? : '';
	}

}
