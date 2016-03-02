<?php
/**
 * Interface LanguageInterface
 *
 * @filesource   LanguageInterface.php
 * @created      11.02.2016
 * @package      chillerlan\BBCode\Language
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Language;

/**
 * @method string parserExceptionCallback($override_language = null)
 * @method string parserExceptionMatchall($override_language = null)
 *
 * @method string codeDisplayCSS($override_language = null)
 * @method string codeDisplayPHP($override_language = null)
 * @method string codeDisplaySQL($override_language = null)
 * @method string codeDisplayXML($override_language = null)
 * @method string codeDisplayHTML($override_language = null)
 * @method string codeDisplayJS($override_language = null)
 * @method string codeDisplayJSON($override_language = null)
 * @method string codeDisplayPRE($override_language = null)
 * @method string codeDisplayCODE($override_language = null)
 * @method string codeDisplayNSIS($override_language = null)
 *
 * @method string expanderDisplayExpander($override_language = null)
 * @method string expanderDisplayQuote($override_language = null)
 * @method string expanderDisplaySpoiler($override_language = null)
 * @method string expanderDisplayTrigger($override_language = null)
 *
 */
interface LanguageInterface{

	/**
	 * It's magic.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	public function __call(string $name, array $arguments);

	/**
	 * Returns a language string for a given key and overrides the current language if desired.
	 *
	 * @param string $key
	 * @param string $override_language (a LanguageInterface FQCN)
	 *
	 * @return mixed
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function string(string $key, string $override_language = null);

}
