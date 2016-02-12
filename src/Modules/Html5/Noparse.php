<?php
/**
 * Class Noparse
 *
 * @filesource   Noparse.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms noparse tags into HTML5
 */
class Noparse extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['noparse'];

	/**
	 * Constructor
	 *
	 * calls self::setBBTemp() in case $bbtemp is set
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 */
	public function __construct(BBTemp $bbtemp = null){
		parent::__construct($bbtemp);

		// set self::$noparse_tags to self::$tags because none of these should be parsed
		$this->noparse_tags = $this->tags;
	}


	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){

		if(empty($this->content)){
			return '';
		}

		// easy stuff: remove the pseudo closing single tags
		$this->clearPseudoClosingTags()->clearEOL(PHP_EOL);

		return '<pre class="bbcode noparse">'.$this->content.'</pre>';
	}

}
