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

namespace chillerlan\BBCodeTest\normal\Modules\Markdown;

use chillerlan\bbcode\Modules\Markdown\Headers;

class MarkdownModuleTest extends MarkdownTestBase{

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
			else if($tag === 'xkcd'){
				$this->assertEquals('https://xkcd.com/221/', $this->parser->parse('[xkcd=221]'));
			}
			else{
				$this->assertEquals($_singletags[$tag], $this->parser->parse('['.$tag.']'));
			}
		}
	}

}
