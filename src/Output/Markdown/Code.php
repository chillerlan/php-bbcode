<?php
/**
 * Class Code
 *
 * @filesource   Code.php
 * @created      25.04.2018
 * @package      chillerlan\BBCode\Output\Markdown
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\Markdown;

class Code extends MarkdownModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['noparse', 'code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json'];

	/**
	 * @var array
	 */
	protected $noparse = ['noparse', 'code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		$this->clearPseudoClosingTags();

		$exceptions = [
			'js'      => 'javascript',
			'pre'     => '',
			'code'    => '',
			'noparse' => '',
		];

		$lang = $exceptions[$this->tag] ?? $this->tag;

		return PHP_EOL.'```'.$lang.PHP_EOL.$this->content.PHP_EOL.'```'.PHP_EOL;
	}

}
