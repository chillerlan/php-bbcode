<?php
/**
 * Interface ModuleInterface
 *
 * @filesource   ModuleInterface.php
 * @created      18.09.2015
 * @package      chillerlan\bbcode\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules;

/**
 * Implements the module specific functionality
 */
interface ModuleInterface{

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform():string;

}
