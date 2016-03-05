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

use chillerlan\BBCodeTest\Includes\Modules\MarkdownTestBase;

class MarkdownModuleTest extends MarkdownTestBase{

	public function testSanitizeCoverage(){
		$this->assertEquals('**&**', $this->parser->parse('[b]&[/b]'));
	}

	public function testNoparseCoverage(){
		$this->assertEquals('[b]&[/b]', $this->parser->parse('[noparse][b]&[/b][/noparse]'));
	}

	/**
	 * @dataProvider emptyTagDataProvider
	 */
	public function testEmptyTags($tag){

		if(!in_array($tag, $this->parser->getSingle())){
			$this->assertEquals('', $this->parser->parse('['.$tag.'][/'.$tag.']'));
		}
		else if($tag === 'xkcd'){
			$this->assertEquals('https://xkcd.com/221/', $this->parser->parse('[xkcd=221]'));
		}
		else{
			$expected = [
				'br' => PHP_EOL,
				'hr' => PHP_EOL.'----'.PHP_EOL,
			][$tag];

			$this->assertEquals($expected, $this->parser->parse('['.$tag.']'));
		}

	}

}
