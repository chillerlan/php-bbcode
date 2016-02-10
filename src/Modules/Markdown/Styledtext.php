<?php
/**
 * Class StyledText
 *
 * @filesource   Styledtext.php
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
 * Transforms several styled text tags into Markdown
 */
class StyledText extends MarkdownBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['b', 'c', 'del', 'i', 's', 'strong'];

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

		$str = [
			'b'      => '**', // bold
			'c'      => '`',  // inline code
			'del'    => '~~', // strikethrough
			'i'      => '_',  // italic
			's'      => '~~', // strikethrough
			'strong' => '**', // bold
		][$this->tag];

		return $this->wrap($this->content, $str);
	}

}
