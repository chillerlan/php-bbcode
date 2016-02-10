<?php
/**
 * Class TextBaseModule
 *
 * @filesource   TextBaseModule.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules\Text
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Text;

use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 * The base module implements the basic functionality for each module (plain text)
 */
class TextBaseModule extends BaseModule implements BaseModuleInterface{

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [

	];

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitize($content){
		// TODO: Implement sanitize() method.
		return 'Implement sanitize() method!';
	}

}
