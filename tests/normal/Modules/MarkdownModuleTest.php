<?php
/**
 *
 * @filesource   MarkdownModuleTest.php
 * @created      02.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules;

use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;

class MarkdownModuleTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		$options = new ParserOptions;
		$options->baseModuleInterface = MarkdownBaseModule::class ;
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}
	
	public function testSanitizeCoverage(){
		$this->assertEquals('**&**', $this->parser->parse('[b]&[/b]'));
	}
	
}
