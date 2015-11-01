<?php
/**
 * Class Code
 *
 * @filesource   Code.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;

/**
 *
 */
class Code extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 */
	protected $tags = ['code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json', 'nsis'];

	/**
	 * @var array
	 */
	protected $noparse_tags = ['code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json', 'nsis'];

	/**
	 * @var array
	 *
	 * @todo: improve
	 */
	private $_code = [
		'css'  => 'Stylesheet/CSS',
		'php'  => 'PHP',
		'sql'  => 'SQL',
		'xml'  => 'XML',
		'html' => 'HTML',
		'js'   => 'JavaScript',
		'json' => 'JSON',
		'pre'  => 'Code',
		'code' => 'Code',
		'nsis' => 'NullSoft Installer Script',
	];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 * @todo translations
	 */
	public function transform(){
		if(empty($this->content)){
			return '';
		}

		$this->check_tag()
		     ->clear_pseudo_closing_tags()
		     ->clear_eol(PHP_EOL);

		$id = $this->random_id();
		$file = $this->get_attribute('file');
		$desc = $this->get_attribute('desc');

		$this->_style = ['display' => $this->get_attribute('hide') ? 'none' : 'block'];

		return '<div data-id="'.$id.'" '.$this->get_title().$this->get_css_class('expander code-header '.$this->tag).'>'
		.$this->_code[$this->tag]
		.($file ? ' - contents of file "<span>'.$file.'</span>"' : '')
		.($desc ? ' - <span>'.$desc.'</span>' : '')
		.'</div>'
		.'<pre id="'.$id.'"'.$this->get_css_class('code-body').$this->get_style().'>'
		.'<code'.$this->get_css_class('language-'.$this->tag).'>'.$this->sanitize($this->content).'</code></pre>';
	}

}
