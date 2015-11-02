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

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\Markup\MarkupBaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

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
		// not using the __NAMESPACE__ constant here for phpdocumentor's sake
		// --> Parse Error: Syntax error, unexpected '.', expecting ',' or ';'
		'\\chillerlan\\bbcode\\Modules\\Html5\\Code',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Containers',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Expanders',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Images',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Links',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Lists',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Noparse',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Simpletext',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Singletags',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Styledtext',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Tables',
		'\\chillerlan\\bbcode\\Modules\\Html5\\Video',
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
