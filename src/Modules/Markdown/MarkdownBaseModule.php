<?php
/**
 * Class MarkdownBaseModule
 *
 * @filesource   MarkdownBaseModule.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markdown;

use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 * The base module implements the basic functionality for each module (GitHub flavoured Markdown)
 *
 * @link https://help.github.com/articles/markdown-basics/
 * @link https://help.github.com/articles/github-flavored-markdown/
 * @link https://help.github.com/articles/writing-on-github/
 * @link https://guides.github.com/features/mastering-markdown/
 */
class MarkdownBaseModule extends BaseModule implements BaseModuleInterface{

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [
		Code::class,
		Headers::class,
		Images::class,
		Links::class,
		Noparse::class,
		Singletags::class,
		StyledText::class,
	];

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitize($content){
		return $content;
	}

}
