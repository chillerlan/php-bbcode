<?php
/**
 *
 * @filesource   ListTest.php
 * @created      04.03.2016
 * @package      chillerlan\bbcodeTest\normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\HTML5;

use chillerlan\bbcodeTest\Includes\Modules\HTML5TestBase;

/**
 * Class ListTest
 */
class ListTest extends HTML5TestBase{

	const INNER_BB = '[*]blah[*]blubb[*]foo[*]bar';
	const INNER_HTML = '<li>blah</li><li>blubb</li><li>foo</li><li>bar</li>';

	public function orderedListDataProvider(){
		return [
			['0', 'decimal-leading-zero'],
			['1', 'decimal'],
			['a', 'lower-alpha'],
			['A', 'upper-alpha'],
			['i', 'lower-roman'],
			['I', 'upper-roman'],
		];
	}

	/**
	 * @dataProvider orderedListDataProvider
	 */
	public function testListModuleOrdered($type, $css){

		$testdata = [
			// type only
			'[list type='.$type.']'.self::INNER_BB.'[/list]'
			=> '<ol class="bb-list '.$css.'">'.self::INNER_HTML.'</ol>',
			// reversed
			'[list type='.$type.' reversed=1]'.self::INNER_BB.'[/list]'
			=> '<ol reversed="true" class="bb-list '.$css.'">'.self::INNER_HTML.'</ol>',
			// start
			'[list=42 type='.$type.']'.self::INNER_BB.'[/list]'
			=> '<ol start="42" class="bb-list '.$css.'">'.self::INNER_HTML.'</ol>',
			// all
			'[list=42 type='.$type.' reversed=1 class=foobar title=WAT]'.self::INNER_BB.'[/list]'
			=> '<ol start="42" reversed="true" title="WAT" class="foobar bb-list '.$css.'">'.self::INNER_HTML.'</ol>',
		];

		foreach($testdata as $bbcode => $expected){
			$this->assertEquals($expected, $this->parser->parse($bbcode));
		}

	}

	public function unorderedListDataProvider(){
		return [
			['c', 'circle'],
			['d', 'disc'],
			['s', 'square'],
		];
	}

	/**
	 * @dataProvider unorderedListDataProvider
	 */
	public function testListModuleUnordered($type, $css){

		$testdata = [
			// type only
			'[list type='.$type.']'.self::INNER_BB.'[/list]',
			// should not happen...
			'[list=42 reversed=1 type='.$type.']'.self::INNER_BB.'[/list]',
		];

		foreach($testdata as $bbcode){
			$this->assertEquals('<ul class="bb-list '.$css.'">'.self::INNER_HTML.'</ul>', $this->parser->parse($bbcode));
		}

	}

}
