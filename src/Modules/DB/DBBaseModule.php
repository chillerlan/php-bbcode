<?php
/**
 * Class DBBaseModule
 *
 * @filesource   DBBaseModule.php
 * @created      28.02.2016
 * @package      chillerlan\bbcode\Modules\DB
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2016 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\DB;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\Html5\{Html5BaseModule, Code, Containers, Expanders, Images, Links, Lists,
	Noparse, Simpletext, Singletags, StyledText, Tables, Video};
use chillerlan\Database\DBOptions;
use chillerlan\Database\Traits\DatabaseTrait;

/**
 *
 */
class DBBaseModule extends Html5BaseModule{
	use DatabaseTrait;

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [
		DBTags::class,
		Code::class,
		Containers::class,
		Expanders::class,
#		Images::class,
#		Links::class,
		Lists::class,
		Noparse::class,
#		Simpletext::class,
#		Singletags::class,
#		StyledText::class,
		Tables::class,
		Video::class,
	];

	/**
	 * @var \chillerlan\Database\Drivers\DBDriverInterface
	 */
	protected $DBDriverInterface;

	/**
	 * DBBaseModule constructor.
	 *
	 * @param \chillerlan\bbcode\BBTemp|null $bbtemp
	 */
	public function __construct(BBTemp $bbtemp = null){
		parent::__construct($bbtemp);

		if($this->parserOptions && $this->parserOptions->DBDriver){
			$this->DBDriverInterface = $this->dbconnect($this->parserOptions->DBDriver, $this->parserOptions->DBOptions);
		}

	}

}
