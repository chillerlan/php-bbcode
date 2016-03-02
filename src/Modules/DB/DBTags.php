<?php
/**
 *
 * @filesource   DBTags.php
 * @created      28.02.2016
 * @package      chillerlan\bbcode\Modules\DB
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2016 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\DB;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Class DBTags
 */
class DBTags extends DBBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = [];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "noparse"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$noparse_tags
	 */
	protected $noparse_tags = [];

	/**
	 * An optional array of tags contained in self::$tags which are marked as "single tag"
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$singletags
	 */
	protected $singletags = [];

	/**
	 * DBTags constructor.
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 */
	public function __construct(BBTemp $bbtemp = null){
		parent::__construct($bbtemp);

		$this->tags         = [];
		$this->noparse_tags = [];
		$this->singletags   = [];
	}

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform():string{
		// TODO: Implement __transform() method.
	}
}
