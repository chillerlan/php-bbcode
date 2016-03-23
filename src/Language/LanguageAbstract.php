<?php
/**
 * Class LanguageAbstract
 *
 * @filesource   LanguageAbstract.php
 * @created      11.02.2016
 * @package      chillerlan\BBCode\Language
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Language;

/**
 * @method string parserExceptionCallback(string $LanguageInterface = null)
 * @method string parserExceptionMatchall(string $LanguageInterface = null)
 *
 * @method string codeDisplayCSS(string $LanguageInterface = null)
 * @method string codeDisplayPHP(string $LanguageInterface = null)
 * @method string codeDisplaySQL(string $LanguageInterface = null)
 * @method string codeDisplayXML(string $LanguageInterface = null)
 * @method string codeDisplayHTML(string $LanguageInterface = null)
 * @method string codeDisplayJS(string $LanguageInterface = null)
 * @method string codeDisplayJSON(string $LanguageInterface = null)
 * @method string codeDisplayPRE(string $LanguageInterface = null)
 * @method string codeDisplayCODE(string $LanguageInterface = null)
 * @method string codeDisplayNSIS(string $LanguageInterface = null)
 *
 * @method string expanderDisplayExpander(string $LanguageInterface = null)
 * @method string expanderDisplayQuote(string $LanguageInterface = null)
 * @method string expanderDisplaySpoiler(string $LanguageInterface = null)
 * @method string expanderDisplayTrigger(string $LanguageInterface = null)
 */
abstract class LanguageAbstract implements LanguageInterface{

	/**
	 * It's magic.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	public function __call(string $name, array $arguments){
		return $this->string($name, ...$arguments);
	}

	/**
	 * Returns a language string for a given key and overrides the current language if desired.
	 *
	 * @param string $key
	 * @param string $LanguageInterface (a LanguageInterface FQCN)
	 *
	 * @return string
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function string(string $key, string $LanguageInterface = null):string{

		if($LanguageInterface){
			return (new $LanguageInterface)->{$key}();
		}

		return $this->{$key};
	}

}
