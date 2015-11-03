<?php
/**
 * Class Headers
 *
 * @filesource   Headers.php
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
 * Transforms several simple text tags into Markdown
 */
class Headers extends MarkdownBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

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

		$header = str_repeat('#', intval(str_replace('h', '', $this->tag)));

		return $header.' '.$this->content.$this->eol_token;
	}

}
