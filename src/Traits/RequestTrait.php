<?php
/**
 *
 * @filesource   RequestTrait.php
 * @created      13.02.2016
 * @package      chillerlan\bbcode\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2016 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Traits;

use chillerlan\TinyCurl\Request;

/**
 * Trait RequestTrait
 */
trait RequestTrait{

	/**
	 * Path to the CA cert file
	 *
	 * @link http://init.haxx.se/ca/cacert.pem
	 * @var string
	 */
	protected $ca_info;

	/**
	 * Embed stuff from anywhere!
	 *
	 * @param string $url
	 * @param array  $params
	 * @param array  $curl_options
	 *
	 * @return \chillerlan\TinyCurl\Response
	 * @throws \chillerlan\TinyCurl\RequestException
	 */
	protected function fetch($url, array $params = [], array $curl_options = []){
		return (new Request($this->ca_info))->fetch($url, $params, $curl_options);
	}

	/**
	 * Sets the path to the CA cert file

	 * @param $ca_info
	 *
	 * @return $this
	 */
	protected function setRequestCA($ca_info){
		$this->ca_info = $ca_info;

		return $this;
	}

}
