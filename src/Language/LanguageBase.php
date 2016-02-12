<?php
/**
 * Class LanguageBase
 *
 * @filesource   LanguageBase.php
 * @created      11.02.2016
 * @package      chillerlan\BBCode\Language
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Language;

use chillerlan\bbcode\Traits\ClassLoaderTrait;

/**
 *
 */
class LanguageBase{
	use ClassLoaderTrait;

	/**
	 * It's magic.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	public function __call($name, $arguments){
		return $this->string($name, ...$arguments);
	}

	/**
	 * Returns a language string for a given key and overrides the current language if desired.
	 *
	 * @param string $key
	 * @param string $override_language (a LanguageInterface FQCN)
	 *
	 * @return mixed
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function string($key, $override_language = null){

		if($override_language){
			return $this->__loadClass($override_language, LanguageInterface::class)->{$key}();
		}

		return $this->{$key};
	}

}
