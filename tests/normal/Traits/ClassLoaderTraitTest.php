<?php
/**
 *
 * @filesource   ClassLoaderTraitTest.php
 * @created      12.02.2016
 * @package      chillerlan\BBCodeTest\normal\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Traits;

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use ReflectionClass;
use stdClass;

class ClassLoaderTraitTest extends \PHPUnit_Framework_TestCase{

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

	/**
	 * @expectedException \chillerlan\BBCode\BBCodeException
	 * @expectedExceptionMessage stdClass does not implement chillerlan\bbcode\Modules\BaseModuleInterface
	 */
	public function testClassLoaderDoesNotImplementException(){
		$options = new ParserOptions;
		$options->base_module = stdClass::class;

		$this->parser = $this->reflectionClass->newInstanceArgs([$options]);
	}

	/**
	 * @expectedException \chillerlan\BBCode\BBCodeException
	 * @expectedExceptionMessage foobar does not exist
	 */
	public function testClassLoaderDoesNotExistException(){
		$options = new ParserOptions;
		$options->base_module = 'foobar';

		$this->parser = $this->reflectionClass->newInstanceArgs([$options]);
	}

}
