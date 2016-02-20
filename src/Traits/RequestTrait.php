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
use chillerlan\TinyCurl\RequestOptions;
use chillerlan\TinyCurl\URL;

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
	 * @return \chillerlan\TinyCurl\Response\ResponseInterface
	 * @throws \chillerlan\TinyCurl\RequestException
	 */
	protected function fetch($url, array $params = [], array $curl_options = []){
		$requestOptions               = new RequestOptions;
		$requestOptions->curl_options = $curl_options;
		$requestOptions->ca_info      = $this->ca_info;

		return (new Request($requestOptions))->fetch(new URL($url, $params));
	}

	/**
	 * Sets the path to the CA cert file
	 *
	 * @param $ca_info
	 *
	 * @return $this
	 */
	protected function setRequestCA($ca_info){
		$this->ca_info = $ca_info;

		return $this;
	}

}
