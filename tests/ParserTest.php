<?php
/**
 *
 * @filesource   ParserTest.php
 * @created      09.02.2016
 * @package      chillerlan\BBCodeTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest;

use chillerlan\bbcode\Parser;
use ReflectionClass;

class ParserTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \ReflectionClass
	 */
	protected $reflectionClass;

	protected function setUp(){
		$this->reflectionClass = new ReflectionClass(Parser::class);
	}

	public function testInstance(){
		$this->assertInstanceOf(Parser::class, $this->reflectionClass->newInstance());
	}


}
