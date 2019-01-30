<?php
/**
 * Class BBCache
 *
 * @filesource   BBCache.php
 * @created      26.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

use Psr\SimpleCache\CacheInterface;

final class BBCache implements CacheInterface{

	/**
	 * @var array
	 */
	protected $cache = [];

	/**
	 * @inheritdoc
	 */
	public function get($key, $default = null){
		return $this->cache[$key] ?? $default;
	}

	/**
	 * @inheritdoc
	 */
	public function set($key, $value, $ttl = null){
		$this->cache[$key] = $value;

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function delete($key){
		unset($this->cache[$key]);

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function clear(){
		$this->cache = [];

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getMultiple($keys, $default = null){
		$data = [];

		foreach($keys as $key){
			$data[$key] = $this->cache[$key] ?? $default;
		}

		return $data;
	}

	/**
	 * @inheritdoc
	 */
	public function setMultiple($values, $ttl = null){

		foreach($values as $key => $value){
			$this->cache[$key] = $value;
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function deleteMultiple($keys){

		foreach($keys as $key){
			unset($this->cache[$key]);
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function has($key){
		return isset($this->cache[$key]);
	}

}
