<?php
/**
 *
 * @filesource   MarkdownModuleTestCritical.php
 * @created      02.03.2016
 * @package      chillerlan\BBCodeTest\critical\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\critical\Modules;

use chillerlan\bbcode\Modules\Markdown\Code;
use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;

class MarkdownModuleTestCritical extends \PHPUnit_Framework_TestCase{

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
	
	public function codeSampleDataProvider(){
		$this->setUp();

		return array_map(function($v){
			return [$v];
		}, array_keys($this->parser->getTagmap(), Code::class));
	}

	/**
	 * @dataProvider codeSampleDataProvider
	 */
	public function testCodeModule($lang){
		$bbcode = file_get_contents(dirname(__FILE__).'/../../bbcode_samples/bbcode_code_'.$lang.'.txt');
		$expected = file_get_contents(dirname(__FILE__).'/../../bbcode_samples/results/markdown_code_'.$lang.'.txt');

		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
