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
 *
 */
interface ModuleInterface{

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 */
	public function _transform();

	/**
	 * Checks the tag and returns the processed bbcode, called from the parser
	 *
	 * @return string
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 */
	public function transform();

	/**
	 * Sets $tag, $attribute, $content and $options
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::set_bbtemp()
	 */
	public function set_bbtemp(BBTemp $bbtemp);

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return \chillerlan\bbcode\Modules\Tagmap
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::get_tags()
	 */
	public function get_tags();

}
