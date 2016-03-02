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
	public function __transform():string{
		$url = $this->checkUrl($this->bbtag());

		if(empty($url)){

			if(empty($this->content) || !$this->checkUrl($this->content)){
				return '';
			}

			return $this->content;
		}

		if(!empty($this->content)){
			return  '['.$this->content.']('.$url.')';
		}

		return $this->checkUrl($url) ? : '';
	}

}
