<?php
/**
 * Class SanitizerAbstract
 *
 * @filesource   SanitizerAbstract.php
 * @created      19.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

use chillerlan\Settings\SettingsContainerInterface;

abstract class SanitizerAbstract implements SanitizerInterface{

	/**
	 * @var \chillerlan\BBCode\BBCodeOptions
	 */
	protected $options;

	/**
	 * SanitizerInterface constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 */
	public function __construct(SettingsContainerInterface $options){
		$this->options = $options;
	}

}
