<?php
/**
 * Interface BaseModuleInterface
 *
 * @filesource   BaseModuleInterface.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules;

use \chillerlan\bbcode\BBTemp;

/**
 *
 */
interface BaseModuleInterface{

	/**
	 * @param $content
	 *
	 * @return string
	 */
	public function sanitize($content);

	/**
	 * Returns a list of the BaseModule's modules
	 *
	 * @return array
	 */
	public function get_info();

	/**
	 * @return $this
	 */
	public function clear_pseudo_closing_tags();

	/**
	 * @param string $str
	 * @param string $eol
	 * @param int    $count
	 *
	 * @return string
	 */
	public function eol($str, $eol = '', $count = null);

	/**
	 * @param string $eol
	 *
	 * @return $this
	 */
	public function clear_eol($eol = '');

	/**
	 * Checks if the module supports the current tag
	 *
	 * @return $this
	 * @throws \chillerlan\bbcode\BBCodeException
	 */
	public function check_tag();

	/**
	 * @param array $array
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function tag_in(array $array, $default = false);

	/**
	 * @param string $name
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get_attribute($name, $default = false);

	/**
	 * @param string $name
	 * @param array  $array
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function attribute_in($name, array $array, $default = false);

	/**
	 * @param string $name
	 * @param array  $array
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function attribute_key_in($name, array $array, $default = false);

	/**
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 *
	 * @return $this
	 */
	public function set_bbtemp(BBTemp $bbtemp);

	/**
	 * Returns an array of tags which the module is able to process
	 *
	 * @return \chillerlan\bbcode\Modules\Tagmap
	 */
	public function get_tags();

	/**
	 * Called from within a module
	 *
	 * @return string
	 */
	public function transform();

}
