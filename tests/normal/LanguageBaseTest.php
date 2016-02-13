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

use chillerlan\bbcode\Language\Chinese;
use chillerlan\bbcode\Language\English;
use chillerlan\bbcode\Language\French;
use chillerlan\bbcode\Language\German;
use chillerlan\bbcode\Language\Spanish;
use ReflectionClass;

class LanguageBaseTest extends \PHPUnit_Framework_TestCase{

	const LANGUAGES = [
		Chinese::class,
		English::class,
		French::class,
	    German::class,
	    Spanish::class,
	];

	public function testIterateLanguage(){
		foreach(self::LANGUAGES as $languageInterface){
			$reflectionClass = new ReflectionClass($languageInterface);
			$lang = $reflectionClass->newInstance();

			foreach($lang as $key => $string){
				$this->assertEquals($lang->string($key, $languageInterface), $lang->{$key}($languageInterface));
			}
		}
	}

}
