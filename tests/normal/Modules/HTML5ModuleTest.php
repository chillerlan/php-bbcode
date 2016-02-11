<?php
/**
 *
 * @filesource   HTML5ModuleTestCritical.php
 * @created      11.02.2016
 * @package      chillerlan\BBCodeTest\normal
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules;

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;

/**
 * Class HTML5ModuleTestCritical
 */
class HTML5ModuleTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		$options = new ParserOptions;
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

	public function testEmptyTags(){
		$singletags = $this->parser->getSingle();
		$exceptions = ['td','th'];

		foreach(array_keys($this->parser->getTagmap()) as $tag){
			if(!in_array($tag, $singletags) && !in_array($tag, $exceptions)){
				$parsed = $this->parser->parse('['.$tag.'][/'.$tag.']');
				$this->assertEquals('', $parsed);
			}
		}
	}

	public function containerDataProvider(){
		return [
			['[div]a div[/div]', '<div style="text-align:left">a div</div>'],
			['[div align=right]an aligned div[/div]', '<div style="text-align:right">an aligned div</div>'],
			['[p]a paragraph[/p]', '<p style="text-align:left">a paragraph</p>'],
			['[p align=right]an aligned paragraph[/p]', '<p style="text-align:right">an aligned paragraph</p>'],
			['[left]left[/left]', '<p style="text-align:left">left</p>'],
			['[left align=right]WAT[/left]', '<p style="text-align:left">WAT</p>'],
			['[right]right[/right]', '<p style="text-align:right">right</p>'],
			['[center]center[/center]', '<p style="text-align:center">center</p>'],
		];
	}

	/**
	 * @dataProvider containerDataProvider
	 */
	public function testContainerModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$this->assertEquals($expected, $parsed);
	}

	public function expanderDataProvider(){
		return [
			['[expander]expander[/expander]', '<div data-id="abcdef12" title="Expander" class="expander-header expander">Expander</div><div id="abcdef12" class="expander-body" style="display:block">expander</div>'],
			['[expander hide=1]expander[/expander]', '<div data-id="abcdef12" title="Expander" class="expander-header expander">Expander</div><div id="abcdef12" class="expander-body" style="display:none">expander</div>'],
			['[quote]quote[/quote]', '<div data-id="abcdef12" title="Quote" class="quote-header expander">Quote</div><div id="abcdef12" class="quote-body" style="display:block">quote</div>'],
			['[quote name=\'some person\' url=http://www.example.com hide=1]quote[/quote]', '<div data-id="abcdef12" title="Quote: some person, source: http://www.example.com" class="quote-header expander">Quote: some person <small>[<a href="http://www.example.com">source</a>]<small></div><div id="abcdef12" class="quote-body" style="display:none">quote</div>'],
			['[spoiler]spoiler[/spoiler]', '<div data-id="abcdef12" title="Spoiler" class="spoiler-header expander">Spoiler</div><div id="abcdef12" class="spoiler-body" style="display:none">spoiler</div>'],
			['[spoiler hide=0]spoiler[/spoiler]', '<div data-id="abcdef12" title="Spoiler" class="spoiler-header expander">Spoiler</div><div id="abcdef12" class="spoiler-body" style="display:none">spoiler</div>'],
			['[trigger]trigger warning[/trigger]', '<div data-id="abcdef12" title="Trigger warning! The following content may be harmful to sensitive audience!" class="trigger-header expander">Trigger warning! The following content may be harmful to sensitive audience!</div><div id="abcdef12" class="trigger-body" style="display:none">trigger warning</div>'],
			['[trigger hide=0]trigger warning[/trigger]', '<div data-id="abcdef12" title="Trigger warning! The following content may be harmful to sensitive audience!" class="trigger-header expander">Trigger warning! The following content may be harmful to sensitive audience!</div><div id="abcdef12" class="trigger-body" style="display:none">trigger warning</div>'],
		];
	}

	/**
	 * @dataProvider expanderDataProvider
	 */
	public function testExpanderModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$parsed = preg_replace('/\"([a-f\d]{8})\"/i', '"abcdef12"', $parsed);
		$this->assertEquals($expected, $parsed);
	}

	public function linkDataProvider(){
		return [
			['[url]https://github.com/chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" class="blank">https://github.com/chillerlan/bbcode</a>'],
			['[url=https://github.com/chillerlan/bbcode]chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" class="blank">chillerlan/bbcode</a>'],
			['[url=https://github.com/chillerlan/bbcode title=\'some stuff\' class=some-css-class]chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" title="some stuff" class="some-css-class blank">chillerlan/bbcode</a>'],
		];
	}

	/**
	 * @dataProvider linkDataProvider
	 */
	public function testLinkModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$this->assertEquals($expected, $parsed);
	}

	public function testListModule(){
		$list_inner_bbcode = '[*]blah[*]blubb[*]foo[*]bar';
		$list_inner_html = '<li>blah</li><li>blubb</li><li>foo</li><li>bar</li>';

		foreach(['0' => 'decimal-leading-zero', '1' => 'decimal', 'a' => 'lower-alpha',
				'A' => 'upper-alpha', 'i' => 'lower-roman', 'I' => 'upper-roman'] as $type => $css){
			// type only
			$parsed = $this->parser->parse('[list type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol style="list-style-type:'.$css.'">'.$list_inner_html.'</ol>', $parsed);
			// reversed
			$parsed = $this->parser->parse('[list type='.$type.' reversed=1]'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol reversed="true" style="list-style-type:'.$css.'">'.$list_inner_html.'</ol>', $parsed);
			// start
			$parsed = $this->parser->parse('[list=42 type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol start="42" style="list-style-type:'.$css.'">'.$list_inner_html.'</ol>', $parsed);
			// all
			$parsed = $this->parser->parse('[list=42 type='.$type.' reversed=1 class=foobar title=WAT]'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol start="42" reversed="true" title="WAT" class="foobar" style="list-style-type:'.$css.'">'.$list_inner_html.'</ol>', $parsed);
		}

		foreach(['c' => 'circle', 'd' => 'disc', 's' => 'square'] as $type => $css){
			$expected = '<ul style="list-style-type:'.$css.'">'.$list_inner_html.'</ul>';
			// type only
			$parsed = $this->parser->parse('[list type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals($expected, $parsed);

			// should not happen...
			$parsed = $this->parser->parse('[list=42 reversed=1 type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals($expected, $parsed);
		}
	}
}
