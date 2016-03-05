<?php
/**
 *
 * @filesource   ContainerTest.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\HTML5;

use chillerlan\BBCodeTest\Includes\Modules\HTML5TestBase;

class ContainerTest extends HTML5TestBase{

	public function containerDataProvider(){
		return [
			['[div]a div[/div]', '<div class="bb-container left">a div</div>'],
			['[div align=right]an aligned div[/div]', '<div class="bb-container right">an aligned div</div>'],
			['[p]a paragraph[/p]', '<p class="bb-container left">a paragraph</p>'],
			['[p align=right]an aligned paragraph[/p]', '<p class="bb-container right">an aligned paragraph</p>'],
			['[left]left[/left]', '<p class="bb-container left">left</p>'],
			['[left align=right]WAT[/left]', '<p class="bb-container left">WAT</p>'],
			['[right]right[/right]', '<p class="bb-container right">right</p>'],
			['[center]center[/center]', '<p class="bb-container center">center</p>'],
		];
	}

	/**
	 * @dataProvider containerDataProvider
	 */
	public function testContainerModule($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
