<?php
/**
 * Class MarkdownSanitizer
 *
 * @filesource   MarkdownSanitizer.php
 * @created      25.04.2018
 * @package      chillerlan\BBCode\Output\Markdown
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\Markdown;

use chillerlan\BBCode\SanitizerAbstract;

final class MarkdownSanitizer extends SanitizerAbstract{

	/**
	 * Sanitizes the input before parsing to prevent vulnerabilities or compatibility problems.
	 *
	 * @param $content string to sanitize
	 *
	 * @return string
	 */
	public function sanitizeInput(string $content):string{
		// TODO: Implement sanitizeInput() method.
		return $content;
#		return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5, 'UTF-8', false);
	}

	/**
	 * Sanitizes the output after parsing to prevent user created xss etc.
	 * Here you can run things like HTMLPurifier or whatever
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function sanitizeOutput(string $content):string{
		// TODO: Implement sanitizeOutput() method.
		return $content;
	}
}
