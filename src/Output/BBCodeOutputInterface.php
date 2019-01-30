<?php
/**
 * Interface BBCodeOutputInterface
 *
 * @filesource   BBCodeOutputInterface.php
 * @created      23.04.2018
 * @package      chillerlan\BBCode\Output
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

interface BBCodeOutputInterface{

	/**
	 * BBCodeOutputInterface constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 * @param \Psr\SimpleCache\CacheInterface  $cache
	 * @param \Psr\Log\LoggerInterface         $logger
	 */
	public function __construct(SettingsContainerInterface $options, CacheInterface $cache, LoggerInterface $logger);

	/**
	 * returns a list of tags the output interface is able to process
	 *
	 * @return array
	 */
	public function getTags():array;

	/**
	 * returns a list of single tag bbcodes that need to be closed before running the parser
	 *
	 * @return array
	 */
	public function getSingleTags():array;

	/**
	 * retruns a list of tags that shouldn't run though the parser
	 *
	 * @return array
	 */
	public function getNoparse():array;

	/**
	 * @return string
	 */
	public function getEOL():string;

	/**
	 * transforms the current bbcode
	 *
	 * @param string $tag
	 * @param array  $attributes
	 * @param string $content
	 * @param string $match
	 * @param int    $callback_count
	 *
	 * @return string
	 */
	public function transform(string $tag, array $attributes, string $content, string $match, int $callback_count):string;

}
