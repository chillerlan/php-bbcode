<?php
/**
 * Interface BBCodeModuleInterface
 *
 * @filesource   BBCodeModuleInterface.php
 * @created      24.04.2018
 * @package      chillerlan\BBCode\Output
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

interface BBCodeModuleInterface{

	/**
	 * BBCodeModuleInterface constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 * @param \Psr\SimpleCache\CacheInterface       $cache
	 * @param \Psr\Log\LoggerInterface              $logger
	 */
	public function __construct(SettingsContainerInterface $options, CacheInterface $cache, LoggerInterface $logger);

	/**
	 * @return array
	 */
	public function getTags():array;

	/**
	 * @return array
	 */
	public function getSingleTags():array;

	/**
	 * @return array
	 */
	public function getNoparse():array;

}
