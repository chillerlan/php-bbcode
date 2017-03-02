<?php
/**
 *
 * @filesource   TextModuleTest.php
 * @created      02.03.2016
 * @package      chillerlan\bbcodeTest\normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\Text;

use chillerlan\bbcode\Modules\Text\TextBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use PHPUnit\Framework\TestCase;

class TextModuleTest extends TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		$options = new ParserOptions;
		$options->baseModuleInterface = TextBaseModule::class ;
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

	public function testSanitizeCoverage(){
		$this->assertEquals('&', $this->parser->parse('[b]&[/b]'));
	}

}
