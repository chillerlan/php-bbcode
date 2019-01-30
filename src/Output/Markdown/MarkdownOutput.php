<?php
/**
 * Class MarkdownOutput
 *
 * @filesource   MarkdownOutput.php
 * @created      25.04.2018
 * @package      chillerlan\BBCode\Output\Markdown
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\Markdown;

use chillerlan\BBCode\Output\BBCodeOutputAbstract;

final class MarkdownOutput extends BBCodeOutputAbstract{

	/**
	 * @var array
	 */
	protected $modules = [
		Basic::class,
		Code::class,
		Headers::class,
		StyledText::class,
	];

}
