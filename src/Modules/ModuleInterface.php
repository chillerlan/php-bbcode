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
	 * Returns the processed bbcode
	 *
	 * @return string a transformed snippet
	 */
	public function transform();

	/**
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::set_bbtemp()
	 */
	public function set_bbtemp(BBTemp $bbtemp);

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return array an array of tagnames
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::get_tags()
	 */
	public function get_tags();

	/**
	 * Returns an array of noparse tags
	 *
	 * @return array
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::get_tags()
	 */
	public function get_noparse_tags();

}
