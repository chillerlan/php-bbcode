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

use \chillerlan\bbcode\BBTemp;

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

	/**
	 * Checks the tag and returns the processed bbcode, called from the parser
	 *
	 * This method is implemented in BaseModuleInterface, no need to overide it.
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 */
	public function transform():string;

	/**
	 * Sets self::$tag, self::$attributes, self::$content and self::$options
	 *
	 * This method is implemented in BaseModuleInterface, no need to overide it.
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::setBBTemp()
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 */
	public function setBBTemp(BBTemp $bbtemp);

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * This method is implemented in BaseModuleInterface, no need to overide it.
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::getTags()
	 */
	public function getTags():Tagmap;

}
