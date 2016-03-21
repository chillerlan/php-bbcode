<?php
/**
 * Class MyAwesomeBaseModule
 *
 * @filesource   MyAwesomeBaseModule.php
 * @created      02.11.2015
 * @package      chillerlan\bbcodeExamples\MyModules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeExamples\MyModules;

use chillerlan\bbcode\Modules\BaseModuleAbstract;

/**
 * The base module implements the basic functionality for each module (custom HTML5)
 */
class MyAwesomeBaseModule extends BaseModuleAbstract{

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [
		MyAwesomeModule::class,
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
	public function sanitize(string $content):string{
		return htmlspecialchars($content, ENT_NOQUOTES|ENT_HTML5, 'UTF-8', false);
	}

}
