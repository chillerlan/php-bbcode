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
	 * Returns an array of tags which the module is able to process
	 *
	 * @return array an array of tagnames
	 * @internal used by \chillerlan\bbcode\Modules\BaseModule::get_tagmap()
	 */
	public function _get_tags();

	/**
	 * Returns an array of noparse tags
	 *
	 * @return array
	 * @internal used by \chillerlan\bbcode\Modules\BaseModule::get_tagmap()
	 */
	public function _get_noparse_tags();

}
