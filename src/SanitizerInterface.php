<?php
/**
 * Interface SanitizerInterface (Sagrotan?)
 *
 * @filesource   SanitizerInterface.php
 * @created      19.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

interface SanitizerInterface{

	/**
	 * Sanitizes the input before parsing to prevent vulnerabilities or compatibility problems.
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitizeInput(string $content):string;

	/**
	 * Sanitizes the output after parsing to prevent user created xss etc.
	 * Here you can run things like HTMLPurifier or whatever
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function sanitizeOutput(string $content):string;

}
