<?php
/**
 *
 * @filesource   StyledTextTest.php
 * @created      04.03.2016
 * @package      normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\HTML5;

use chillerlan\bbcodeTest\Includes\Modules\HTML5TestBase;

/**
 * Class StyledTextTest
 */
class StyledTextTest extends HTML5TestBase{

	public function styledTextDataProvider(){
		return [
			['[color=#424242]color[/color]', '<span class="bb-text color" style="color:#424242">color</span>'],
			['[font=Helvetica]font[/font]', '<span class="bb-text font" style="font-family:Helvetica">font</span>'],
			['[size=42px]size[/size]', '<span class="bb-text size" style="font-size:42px">size</span>'],
			['[tt]typewriter[/tt]', '<span class="bb-text typewriter">typewriter</span>'],
			['[i]italic[/i]', '<span class="bb-text italic">italic</span>'],
			['[b]bold[/b]', '<span class="bb-text bold">bold</span>'],
			['[s]strikethrough[/s]', '<span class="bb-text linethrough">strikethrough</span>'],
			['[u]underline[/u]', '<span class="bb-text underline">underline</span>'],
		];
	}

	/**
	 * @dataProvider styledTextDataProvider
	 */
	public function testStyledTextModule($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
