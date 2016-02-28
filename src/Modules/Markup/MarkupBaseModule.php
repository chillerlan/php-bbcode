<?php
/**
 * Class MarkupBaseModule
 *
 * @filesource   MarkupBaseModule.php
 * @created      18.10.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markup;

use chillerlan\bbcode\Modules\{BaseModule, BaseModuleInterface};

/**
 * The base module implements the basic functionality for each module (Markup: (X)HTML, XML, etc.)
 */
class MarkupBaseModule extends BaseModule implements BaseModuleInterface{

	/**
	 * Allowed text-align modes
	 *
	 * @var array
	 */
	const TEXT_ALIGN = [
		'left',
		'center',
		'right',
		'justify',
		'start',
		'end',
		'inherit',
	];

	/**
	 * Allowed vertical-align modes
	 *
	 * @var array
	 */
	const VERTICAL_ALIGN = [
		'baseline',
		'sub',
		'super',
		'text-top',
		'text-bottom',
		'middle',
		'top',
		'bottom',
		'inherit',
	];

	/**
	 * Holds an array of FQN strings to the current base module's children
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\ModuleInfo::$modules
	 */
	protected $modules = [];

	/**
	 * Allowed css fonts
	 *
	 * @var array
	 * @todo allowed fonts -> options
	 */
	protected $allowed_fonts = [
		'Arial',
		'Chicago',
		'Courier New',
		'Geneva',
		'Georgia',
		'Helvetica',
		'Impact',
		'Lucida Sans',
		'Tahoma',
		'Times New Roman',
		'Trebuchet MS',
		'Verdana',
		'Courier, monospace',
	]; // ban comic sans: 'Comic Sans MS',

	/**
	 * @todo allowed_colors -> options?
	 *
	protected $css_colors = 'aliceblue,antiquewhite,aqua,aquamarine,...';
	 */

	/**
	 * @todo css_class_prefix -> options?
	 *
	protected $css_class_prefix = 'bbcode-';
	 */

	/**
	 * Sanitizes the content to prevent vulnerabilities or compatibility problems
	 *
	 * @param string $content content to sanitize
	 *
	 * @return string
	 */
	public function sanitize(string $content):string{
		return htmlspecialchars($content, ENT_NOQUOTES, 'UTF-8', false);
	}

	/**
	 * Returns a random crc32 hash
	 *
	 * @return string usable as (X)HTML/XML element id
	 *
	 * @see https://xkcd.com/221/
	 */
	protected function randomID():string{
		return hash('crc32b', mt_rand().microtime(true));
	}

	/**
	 * Returns a cleaned string containing all given css classnames
	 * (class bbcode attribute *and* parameter)
	 *
	 * @param array $additional_classes
	 *
	 * @return string usable as (X)HTML/XML class attribute
	 */
	protected function getCssClass(array $additional_classes = []):string{
		$classes = $this->getAttribute('class', '').' '.implode(' ', $additional_classes);
		$classes =preg_replace('/[^a-z\d\- ]/i', '', $classes);
		$classes = trim($classes);

		return !empty($classes) ? ' class="'.$classes.'"' : '';
	}

	/**
	 * Returns a string containing the given title
	 * (class bbcode attribute *or* parameter)
	 *
	 * @param string $title
	 *
	 * @return string usable as (X)HTML/XML title attribute
	 */
	protected function getTitle(string $title = ''):string{
		$title = $this->getAttribute('title', $title);

		// @todo: filter

		return !empty($title) ? ' title="'.$title.'"' : '';
	}

	/**
	 * Collects all properties of self::$_style and merges them into a css-style compatible string
	 *
	 * @param array $style
	 *
	 * @return string usable as (X)HTML/XML style attribute
	 */
	protected function getStyle(array $style = []):string{
		$out = [];

		foreach($style as $property => $value){
			// @todo: if(in_array($property, $allowed))?
			// handle exclusions of common user definable properties
			switch(true){
				// color
				case in_array($property, ['background-color', 'color'])
					&& !preg_match('/^#([a-f\d]{3}){1,2}$/i', $value):
				// sizes
				case in_array($property, ['font-size', 'line-height', 'width', 'height'])
					&& !preg_match('/^[\d\.]+(px|pt|em|%)$/', $value):
					// yep, it's a merciless fall-through
					$value = false;
					break;
			}

			if($value){
				$out[] = $property.':'.$value;
			}
		}

		return !empty($out) ? ' style="'.implode(';', $out).'"' : '';
	}

}
