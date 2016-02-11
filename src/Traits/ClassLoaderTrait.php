<?php
/**
 *
 * @filesource   ClassLoaderTrait.php
 * @created      11.02.2016
 * @package      chillerlan\bbcode\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Traits;

use chillerlan\bbcode\BBCodeException;
use ReflectionClass;

/**
 * Trait ClassLoaderTrait
 */
trait ClassLoaderTrait{

	/**
	 * A simple class loader
	 *
	 * @param string $class     FQCN
	 * @param string $interface FQCN
	 *
	 * @param mixed  $params    [optional] the following arguments are optional and will be passed to the class constructor if present.
	 *
	 * @link https://github.com/chillerlan/framework/blob/master/src/Core/Traits/ClassLoaderTrait.php
	 *
	 * @return object of type $interface
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	protected function __loadClass($class, $interface, ...$params){ // phpDocumentor stumbles across the ... syntax
		if(class_exists($class)){
			$reflectionClass = new ReflectionClass($class);

			if(!$reflectionClass->implementsInterface($interface)){
				throw new BBCodeException($class.' does not implement '.$interface);
			}

			return $reflectionClass->newInstanceArgs($params);
		}

		throw new BBCodeException($class.' does not exist');
	}


}
