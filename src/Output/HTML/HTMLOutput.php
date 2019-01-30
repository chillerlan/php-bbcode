<?php
/**
 * Class HTMLOutput
 *
 * @filesource   HTMLOutput.php
 * @created      23.04.2018
 * @package      chillerlan\BBCode\Output\HTML
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\HTML;

use chillerlan\BBCode\Output\BBCodeOutputAbstract;

// @todo: sanitize quotes in attributes
final class HTMLOutput extends BBCodeOutputAbstract{

	/**
	 * @var array
	 */
	protected $modules = [
		Basic::class,
		Code::class,
		Containers::class,
		Expanders::class,
		Tables::class,
	];

	/**
	 * @var string
	 */
	protected $eol = '<br/>';

}
