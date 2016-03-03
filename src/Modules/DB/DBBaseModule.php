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
use chillerlan\bbcode\Modules\Markup\MarkupBaseModule;
use chillerlan\Database\Traits\DatabaseTrait;

/**
 *
 */
class DBBaseModule extends MarkupBaseModule{
	use DatabaseTrait;

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [
		DBTags::class,
	];

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

		$this->tags         = [];
		$this->noparse_tags = [];
		$this->singletags   = [];

		if($this->parserOptions && $this->parserOptions->DBDriver){
			$this->DBDriverInterface = $this->dbconnect($this->parserOptions->DBDriver, $this->parserOptions->DBOptions);
		}

	}

	/**
	 * Holds the current base module's EOL token which will replace any newlines
	 *
	 * @var string
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$eol_token
	 */
	protected $eol_token = '<br />';

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitize(string $content):string{
		return htmlspecialchars($content, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5, 'UTF-8', false);
	}

}
