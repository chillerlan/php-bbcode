<?php
/**
 *
 * @filesource   TableTest.php
 * @created      04.03.2016
 * @package      chillerlan\bbcodeTest\normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\HTML5;

use chillerlan\bbcodeTest\Includes\Modules\HTML5TestBase;

/**
 * Class TableTest
 */
class TableTest extends HTML5TestBase{

	public function tableDataProvider(){
		return [
			['[table width=300px class=mybbtyble][tr][td]foobar[/td][/tr][/table]', '<table class="mybbtyble bb-table" style="width:300px"><tr><td>foobar</td></tr></table>'],
			['[table width=300px class=mybbtyble][caption][b]caption[/b][/caption][tr][td]foobar[/td][/tr][/table]', '<table class="mybbtyble bb-table" style="width:300px"><caption><span class="bb-text bold">caption</span></caption><tr><td>foobar</td></tr></table>'],
			['[table][caption][b]caption[/b][/caption][colgroup][col][col][/colgroup][colgroup][col span=2][/colgroup][tbody][tr][td]1[/td][td]2[/td][/tr][/tbody][tbody][tr][td]3[/td][td]4[/td][/tr][/tbody][/table]', '<table class="bb-table"><caption><span class="bb-text bold">caption</span></caption><colgroup><col /><col /></colgroup><colgroup><col span="2" /></colgroup><tbody><tr><td>1</td><td>2</td></tr></tbody><tbody><tr><td>3</td><td>4</td></tr></tbody></table>'],
			['[table][tr][th abbr=ched nowrap=1 colspan=6 rowspan=2]wat[/th][/tr][tr][td align=foobar]1[/td][td align=right]2[/td][td valign=foobar]3[/td][td valign=top]4[/td][td width=100florps]5[/td][td width=100px]6[/td][/tr][/table]', '<table class="bb-table"><tr><th colspan="6" rowspan="2" abbr="ched" style="white-space:nowrap">wat</th></tr><tr><td>1</td><td style="text-align:right">2</td><td>3</td><td style="vertical-align:top">4</td><td>5</td><td style="width:100px">6</td></tr></table>'],
		];
	}

	/**
	 * @dataProvider tableDataProvider
	 */
	public function testTableModule($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
