<?php
/**
 *
 * @filesource   HeaderTest.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\Markdown;
use chillerlan\bbcode\Modules\Markdown\Headers;

/**
 * Class HeaderTest
 */
class HeaderTest extends MarkdownTestBase{

	public function headerDataProvider(){
		$this->setUp();

		return [array_keys($this->parser->getTagmap(), Headers::class)];
	}

	/**
	 * @dataProvider headerDataProvider
	 */
	public function testHeaderModule($tag){
		$expected = str_repeat('#', preg_replace('/[^\d]/', '', $tag)).' test'.PHP_EOL;

		$this->assertEquals($expected, $this->parser->parse('['.$tag.']test[/'.$tag.']'));
	}


}
