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

use chillerlan\bbcode\Modules\Markdown\Headers;
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

	public function testNoparseCoverage(){
		$this->assertEquals('[b]&[/b]', $this->parser->parse('[noparse][b]&[/b][/noparse]'));
	}

	public function testEmptyTags(){
		$singletags  = $this->parser->getSingle();
		$_singletags = [
			'br'    => PHP_EOL,
			'hr'    => PHP_EOL.'----'.PHP_EOL,
		];

		foreach(array_keys($this->parser->getTagmap()) as $tag){
			if(!in_array($tag, $singletags)){
				$this->assertEquals('', $this->parser->parse('['.$tag.'][/'.$tag.']'));
			}
			else{
				$this->assertEquals($_singletags[$tag], $this->parser->parse('['.$tag.']'));
			}
		}
	}

	public function headerDataProvider(){
		$this->setUp();

		return array_map(function($v){
			return [$v];
		}, array_keys($this->parser->getTagmap(), Headers::class));
	}

	/**
	 * @dataProvider headerDataProvider
	 */
	public function testHeaderModule($tag){
		$expected = str_repeat('#', preg_replace('/[^\d]/', '', $tag)).' test'.PHP_EOL;

		$this->assertEquals($expected, $this->parser->parse('['.$tag.']test[/'.$tag.']'));
	}

	public function testImageModule(){
		$this->assertEquals('', $this->parser->parse('[img]javascript:alert(\'XSS\');[/img]'));
		$this->assertEquals('![image](https://travis-ci.org/chillerlan/bbcode.svg)', $this->parser->parse('[img]https://travis-ci.org/chillerlan/bbcode.svg[/img]'));
		$this->assertEquals('![foobar](https://travis-ci.org/chillerlan/bbcode.svg)', $this->parser->parse('[img alt=foobar]https://travis-ci.org/chillerlan/bbcode.svg[/img]'));
	}

	public function linkDataProvider(){
		return [
			['', '[url]javascript:alert(\'XSS\');[/url]'],
			['', '[url=javascript:alert(\'XSS\');]test[/url]'],
			['', '[url=javascript:alert(\'XSS\');]javascript:alert(\'XSS\');[/url]'],
			['https://travis-ci.org/chillerlan/bbcode', '[url]https://travis-ci.org/chillerlan/bbcode[/url]'],
			['https://travis-ci.org/chillerlan/bbcode', '[url=https://travis-ci.org/chillerlan/bbcode][/url]'],
			['[Travis CI: chillerlan/bbcode](https://travis-ci.org/chillerlan/bbcode)', '[url=https://travis-ci.org/chillerlan/bbcode]Travis CI: chillerlan/bbcode[/url]'],
			['http://youtu.be/6r1-HTiwGiY', '[video]http://youtu.be/6r1-HTiwGiY[/video]'],
		];
	}

	/**
	 * @dataProvider linkDataProvider
	 */
	public function testLinkModule($expected, $bbcode){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

	public function styledTextDataProvider(){
		return [
			['[b]bold[/b]', '**bold**'],
			['[strong]bold[/strong]', '**bold**'],
			['[del]strikethrough[/del]', '~~strikethrough~~'],
			['[s]strikethrough[/s]', '~~strikethrough~~'],
			['[c]inline-code[/c]', '`inline-code`'],
			['[i]italic[/i]', '_italic_'],
		];
	}

	/**
	 * @dataProvider styledTextDataProvider
	 */
	public function testStyledTextModule($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}


}
