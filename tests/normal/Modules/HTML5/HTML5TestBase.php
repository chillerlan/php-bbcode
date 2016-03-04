<?php
/**
 *
 * @filesource   HTML5TestBase.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\HTML5;

use chillerlan\bbcode\Modules\Html5\Html5BaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use chillerlan\BBCodeTest\normal\Modules\ModuleTestBase;

/**
 * Class HTML5TestBase
 */
class HTML5TestBase extends ModuleTestBase{

	protected function setUp(){

		$options = new ParserOptions;
		$options->ca_info             = self::TESTDIR.'test-cacert.pem';
		$options->baseModuleInterface = Html5BaseModule::class;
		$options->allow_all           = true;

		$this->parser = new Parser($options);
	}

}
