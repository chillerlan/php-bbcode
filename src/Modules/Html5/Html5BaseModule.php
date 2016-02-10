<?php
/**
 * Class Html5BaseModule
 *
 * @filesource   Html5BaseModule.php
 * @created      13.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\Modules\BaseModuleInterface;
use chillerlan\bbcode\Modules\Markup\MarkupBaseModule;

/**
 * The base module implements the basic functionality for each module (HTML5)
 */
class Html5BaseModule extends MarkupBaseModule implements BaseModuleInterface{

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [
		Code::class,
		Containers::class,
		Expanders::class,
		Images::class,
		Links::class,
		Lists::class,
		Noparse::class,
		Simpletext::class,
		Singletags::class,
		StyledText::class,
		Tables::class,
		Video::class,
	];

	/**
	 * Holds the current base module's EOL token which will replace any newlines
	 *
	 * @var string
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$eol_token
	 */
	protected $eol_token = '<br />';

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitize($content){
		return htmlspecialchars($content, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5, 'UTF-8', false);
	}

}
