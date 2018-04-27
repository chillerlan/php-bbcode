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

use chillerlan\Traits\ContainerInterface;

abstract class SanitizerAbstract implements SanitizerInterface{

	/**
	 * @var \chillerlan\BBCode\BBCodeOptions
	 */
	protected $options;

	/**
	 * SanitizerInterface constructor.
	 *
	 * @param \chillerlan\Traits\ContainerInterface $options
	 */
	public function __construct(ContainerInterface $options){
		$this->options = $options;
	}

}
