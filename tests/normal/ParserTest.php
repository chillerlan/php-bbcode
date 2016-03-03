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

namespace chillerlan\BBCodeTest\normal;

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use ReflectionClass;

class ParserTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	/**
	 * @var \ReflectionClass
	 */
	protected $reflectionClass;

	protected function setUp(){
		$this->reflectionClass = new ReflectionClass(Parser::class);
	}

	public function testInstance(){
		$this->parser = $this->reflectionClass->newInstance();
		$this->assertInstanceOf(Parser::class, $this->parser);
	}

	public function testGetAllowed(){
		$options = new ParserOptions;
		$options->allowed_tags = ['noparse','code','img'];

		$method = $this->reflectionClass->getMethod('getAllowed');
		$this->parser = $this->reflectionClass->newInstanceArgs([$options]);
		$this->assertEquals(['code','img','noparse'], $method->invoke($this->parser));
	}

	public function testGetNoparse(){
		$noparse_tags = ['code','css','html','js','json','noparse','nsis','php','pre','sql','xml'];

		$method = $this->reflectionClass->getMethod('getNoparse');
		$this->parser = $this->reflectionClass->newInstance();
		$this->assertEquals($noparse_tags, $method->invoke($this->parser));
	}

	public function testGetSingle(){
		$singletags = ['br','clear','col','hr','xkcd'];

		$method = $this->reflectionClass->getMethod('getSingle');
		$this->parser = $this->reflectionClass->newInstance();
		$this->assertEquals($singletags, $method->invoke($this->parser));
	}

}