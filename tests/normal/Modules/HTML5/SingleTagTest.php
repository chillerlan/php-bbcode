<?php
/**
 *
 * @filesource   SingleTagTest.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\HTML5;

/**
 * Class SingleTagTest
 */
class SingleTagTest extends HTML5TestBase{

	public function singletagDataProvider(){
		return [
			['[br]', '<br />'],
			['[hr]', '<hr />'],
			['[clear]', '<br class="bb-clear both" />'],
			['[col]', '<col />'],
			['[xkcd]', ''],
			['[xkcd=1530]', '<a href="https://xkcd.com/1530/" class="bb-xkcd">[xkcd 1530]</a>'],
		];
	}

	/**
	 * @dataProvider singletagDataProvider
	 */
	public function testSingletagModule($tag, $expected){
		$this->assertEquals($expected, $this->parser->parse($tag));
	}

}
