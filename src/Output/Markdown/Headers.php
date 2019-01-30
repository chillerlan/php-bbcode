<?php
/**
 * Class Headers
 *
 * @filesource   ${FILE_NAME}
 * @created      25.04.2018
 * @package      chillerlan\BBCode\Output\Markdown
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\Markdown;

class Headers extends MarkdownModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		return str_repeat('#', (int)str_replace('h', '', $this->tag)).' '.$this->content.PHP_EOL;
	}

}
