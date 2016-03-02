<?php
/**
 * Class Spanish
 *
 * @filesource   Spanish.php
 * @created      11.02.2016
 * @package      chillerlan\bbcode\Language
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
 */
class Spanish extends LanguageBase implements LanguageInterface{

	public $parserExceptionCallback = '';
	public $parserExceptionMatchall = '';

	public $codeDisplayCSS  = '';
	public $codeDisplayPHP  = '';
	public $codeDisplaySQL  = '';
	public $codeDisplayXML  = '';
	public $codeDisplayHTML = '';
	public $codeDisplayJS   = '';
	public $codeDisplayJSON = '';
	public $codeDisplayPRE  = '';
	public $codeDisplayCODE = '';
	public $codeDisplayNSIS = '';

	public $expanderDisplayExpander = '';
	public $expanderDisplayQuote    = '';
	public $expanderDisplaySpoiler  = '';
	public $expanderDisplayTrigger  = '';

}
