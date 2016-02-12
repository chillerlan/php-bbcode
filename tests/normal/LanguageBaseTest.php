<?php
/**
 *
 * @filesource   LanguageBaseTest.php
 * @created      12.02.2016
 * @package      chillerlan\BBCodeTest\normal\Language
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Language;

use chillerlan\bbcode\Language\English;
use chillerlan\bbcode\Language\German;

class LanguageBaseTest extends \PHPUnit_Framework_TestCase{

	protected function setUp(){

	}

	public function testIterateLanguage(){
		$lang = new English;
		foreach($lang as $key => $string){
			$this->assertEquals($lang->string($key), $lang->{$key}());
			$this->assertEquals($lang->string($key, German::class), $lang->{$key}(German::class));
		}
	}

}
