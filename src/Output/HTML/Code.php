<?php
/**
 * Class Code
 *
 * @filesource   Code.php
 * @created      25.04.2018
 * @package      chillerlan\BBCode\Output\HTML
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\HTML;

class Code extends HTMLModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json', 'nsis'];

	/**
	 * @var array
	 */
	protected $noparse = ['code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json', 'nsis'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		$this->clearPseudoClosingTags();

		$id   = $this->randomID();
		$desc = $this->getAttribute('desc');

		// @todo
		return '<div data-id="'.$id.'" class="expander code-header '.$this->tag.'">'.($desc ? ' - <span>'.$desc.'</span>' : '').'</div>'
		       .'<pre id="'.$id.'" class="code-body" style="display:'.($this->getAttribute('hide') ? 'none' : 'block').';">'
		       .'<code class="language-'.$this->tag.'">'.$this->content.'</code></pre>'; // sanitize
	}

}
