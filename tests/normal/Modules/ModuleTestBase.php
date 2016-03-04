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

namespace chillerlan\BBCodeTest\normal\Modules;

class ModuleTestBase extends \PHPUnit_Framework_TestCase{

	/**
	 * @todo TRAVIS REMINDER!
	 * @link https://docs.travis-ci.com/user/encrypting-files/
	 */
	const DOTENV = '.env_example';
	const TESTDIR = __DIR__.'/../../';

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;
	
	public function testInstance(){

	}

}
