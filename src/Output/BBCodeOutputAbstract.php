<?php
/**
 * Class BBCodeOutputAbstract
 *
 * @filesource   BBCodeOutputAbstract.php
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

abstract class BBCodeOutputAbstract implements BBCodeOutputInterface{

	/**
	 * @var string[]
	 */
	protected $modules = [];

	/**
	 * @var string[]
	 */
	protected $tagmap = [];

	/**
	 * Holds an array of singletags
	 *
	 * @var string[]
	 */
	protected $singletags = [];

	/**
	 * Holds an array of noparse tags
	 *
	 * @var string[]
	 */
	protected $noparse = [];

	/**
	 * @var string
	 */
	protected $eol = PHP_EOL;

	/**
	 * @var \chillerlan\BBCode\BBCodeOptions
	 */
	protected $options;

	/**
	 * @var \Psr\SimpleCache\CacheInterface
	 */
	protected $cache;

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * @var \chillerlan\BBCode\Output\BBCodeModuleInterface[]
	 */
	protected $moduleInterfaces = [];

	/**
	 * BBCodeOutputInterface constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface $options
	 * @param \Psr\SimpleCache\CacheInterface  $cache
	 * @param \Psr\Log\LoggerInterface         $logger
	 */
	public function __construct(SettingsContainerInterface $options, CacheInterface $cache, LoggerInterface $logger){
		$options->replacement_eol = $options->replacement_eol ?? $this->eol;

		$this->options = $options;
		$this->cache   = $cache;
		$this->logger  = $logger;

		foreach($this->modules as $module){
			/** @var \chillerlan\BBCode\Output\BBCodeModuleInterface $moduleInterface */
			$moduleInterface = new $module($this->options, $this->cache, $this->logger);

			foreach($moduleInterface->getTags() as $tag){
				$this->tagmap[$tag] = $module;
			}

			$this->noparse    = array_merge($this->noparse, $moduleInterface->getNoparse());
			$this->singletags = array_merge($this->singletags, $moduleInterface->getSingleTags());

			$this->moduleInterfaces[$module] = $moduleInterface;
		}

	}

	/**
	 * @inheritdoc
	 */
	public function getTags():array {
		return array_keys($this->tagmap);
	}

	/**
	 * @inheritdoc
	 */
	public function getSingleTags():array {
		return $this->singletags;
	}

	/**
	 * @inheritdoc
	 */
	public function getNoparse():array{
		return $this->noparse;
	}

	/**
	 * @return string
	 */
	public function getEOL():string{
		return $this->options->replacement_eol;
	}

	/**
	 * @inheritdoc
	 */
	public function transform(string $tag, array $attributes, string $content, string $match, int $callback_count):string{

		if(!in_array($tag, array_keys($this->tagmap), true)){
			return $match; // $content
		}

		return call_user_func_array([$this->moduleInterfaces[$this->tagmap[$tag]], $tag], [$tag, $attributes, $content, $match, $callback_count]);
	}

}
