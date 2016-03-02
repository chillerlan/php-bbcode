<?php
/**
 * Class English
 *
 * @filesource   English.php
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
class English extends LanguageBase implements LanguageInterface{

	public $parserExceptionCallback = 'preg_replace_callback() died on [%1$s] due to a %2$s (%3$s)';
	public $parserExceptionMatchall = 'preg_match_all() died due to a %1$s (%2$s)';

	public $codeDisplayCSS  = 'Stylesheet/CSS';
	public $codeDisplayPHP  = 'PHP';
	public $codeDisplaySQL  = 'SQL';
	public $codeDisplayXML  = 'XML';
	public $codeDisplayHTML = 'HTML';
	public $codeDisplayJS   = 'JavaScript';
	public $codeDisplayJSON = 'JSON';
	public $codeDisplayPRE  = 'Code';
	public $codeDisplayCODE = 'Code';
	public $codeDisplayNSIS = 'NullSoft Installer Script';

	public $expanderDisplayExpander = 'Expander';
	public $expanderDisplayQuote    = 'Quote';
	public $expanderDisplaySpoiler  = 'Spoiler';
	public $expanderDisplayTrigger  = 'Trigger warning! The following content may be harmful to sensitive audience!';

}
