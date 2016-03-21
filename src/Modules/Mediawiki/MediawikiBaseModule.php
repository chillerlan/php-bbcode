<?php
/**
 * Class MediawikiBaseModule
 *
 * @filesource   MediawikiBaseModule.php
 * @created      04.11.2015
 * @package      chillerlan\bbcode\Modules\Mediawiki
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Mediawiki;

use chillerlan\bbcode\Modules\BaseModuleAbstract;

/**
 * The base module implements the basic functionality for each module (Mediawiki)
 */
class MediawikiBaseModule extends BaseModuleAbstract{

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
	public function sanitize(string $content):string{
		// TODO: Implement sanitize() method.
		return $content;
	}

}
