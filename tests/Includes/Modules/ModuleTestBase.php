<?php
/**
 *
 * @filesource   ModuleTestBase.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\Includes\Modules;

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;

class ModuleTestBase extends \PHPUnit_Framework_TestCase{

	/**
	 * @todo TRAVIS REMINDER!
	 * @link https://docs.travis-ci.com/user/encrypting-files/
	 */
	const DOTENV = '.env_example';
	const TESTDIR = __DIR__.'/../../';

	protected $baseModule;
	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){

		$options = new ParserOptions;
		$options->ca_info             = self::TESTDIR.'test-cacert.pem';
		$options->baseModuleInterface = $this->baseModule;
		$options->allow_all           = true;

		$this->parser = new Parser($options);
	}

	public function emptyTagDataProvider($tag){
		$this->setUp();
		
		return array_map(function($v){
			return [$v];
		}, array_keys($this->parser->getTagmap()));
	}

}
