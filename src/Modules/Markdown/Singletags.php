<?php
/**
 * Class Singletags
 *
 * @filesource   Singletags.php
 * @created      03.11.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markdown;

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms several single tags into Markdown
 */
class Singletags extends MarkdownBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['br', 'hr', 'xkcd'];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "single tag"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = ['br', 'hr', 'xkcd'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform():string{

		return [
			'br' => $this->eol_token,
			'hr' => $this->wrap('----', $this->eol_token),
			'xkcd' => 'https://xkcd.com/'.intval($this->bbtag()).'/',
		][$this->tag];
	}

}
