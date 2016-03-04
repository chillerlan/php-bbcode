<?php
/**
 *
 * @filesource   HTML5ModuleTestCritical.php
 * @created      11.02.2016
 * @package      chillerlan\BBCodeTest\normal\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\HTML5;

use chillerlan\bbcode\Modules\Html5\Simpletext;

/**
 * Class HTML5ModuleTestCritical
 */
class HTML5ModuleTest extends HTML5TestBase{

	public function testSanitizeCoverage(){
		$this->assertEquals('<span class="bb-text bold">&amp;</span>', $this->parser->parse('[b]&[/b]'));
	}

	public function emptyTagDataProvider($tag){
		$this->setUp();
		return [array_keys($this->parser->getTagmap())];
	}

	/**
	 * @dataProvider emptyTagDataProvider
	 */
	public function testEmptyTags($tag){
		$exceptions  = ['tr','td','th'];

		if(!in_array($tag, $this->parser->getSingle()) && !in_array($tag, $exceptions)){
			$this->assertEquals('', $this->parser->parse('['.$tag.'][/'.$tag.']'));
		}
		else if(in_array($tag, $exceptions)){
			$this->assertEquals('<'.$tag.'></'.$tag.'>', $this->parser->parse('['.$tag.'][/'.$tag.']'));
		}
		else{
			$expected = [
				'br'    => '<br />',
				'col'   => '<col />',
				'hr'    => '<hr />',
				'xkcd'  => '',
				'clear' => '<br class="bb-clear both" />',
			][$tag];

			$this->assertEquals($expected, $this->parser->parse('['.$tag.']'));
		}
	}

}
