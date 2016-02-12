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
	public function __call($name, $arguments);

	/**
	 * Returns a language string for a given key and overrides the current language if desired.
	 *
	 * @param string $key
	 * @param string $override_language (a LanguageInterface FQCN)
	 *
	 * @return mixed
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function string($key, $override_language = null);

}
