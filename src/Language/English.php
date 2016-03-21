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
 *
 */
class English extends LanguageAbstract{

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
