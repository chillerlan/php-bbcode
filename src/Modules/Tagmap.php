<?php
/**
 * Class Tagmap
 *
 * @filesource   Tagmap.php
 * @created      21.10.2015
 * @package      chillerlan\bbcode\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules;

/**
 * Holds the tags which a module is able to process
 *
 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::getTags()
 */
class Tagmap{

	/**
	 * An array of tags a module is able to process
	 *
	 * @var array
	 */
	public $tags = [];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "noparse"
	 *
	 * @var array
	 */
	public $noparse_tags = [];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "single tag"
	 *
	 * @var array
	 */
	public $singletags = [];

}
