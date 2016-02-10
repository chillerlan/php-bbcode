<?php
/**
 * Class BaseModuleInfo
 *
 * @filesource   BaseModuleInfo.php
 * @created      21.10.2015
 * @package      chillerlan\bbcode\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules;

/**
 * Holds the current base module's capabilities info
 *
 * @see \chillerlan\bbcode\Modules\BaseModule::getInfo()
 */
class BaseModuleInfo{

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 */
	public $modules = [];

	/**
	 * Holds the current base module's EOL token which will replace any newlines
	 *
	 * @var string
	 */
	public $eol_token = PHP_EOL;

}
