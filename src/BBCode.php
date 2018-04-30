<?php
/**
 * Class BBCode
 *
 * @filesource   BBCode.php
 * @created      19.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

use chillerlan\BBCode\Output\BBCodeOutputInterface;
use chillerlan\Traits\{
	ClassLoader, ContainerInterface
};
use Psr\Log\{
	LoggerAwareInterface, LoggerAwareTrait, LoggerInterface, NullLogger
};
use Psr\SimpleCache\CacheInterface;

class BBCode implements LoggerAwareInterface{
	use ClassLoader, LoggerAwareTrait;

	/**
	 * @var \chillerlan\BBCode\BBCodeOptions|\chillerlan\Traits\ContainerInterface
	 */
	protected $options;

	/**
	 * @var \Psr\SimpleCache\CacheInterface
	 */
	protected $cache;

	/**
	 * @var \chillerlan\BBCode\SanitizerInterface
	 */
	protected $sanitizerInterface;

	/**
	 * @var \chillerlan\BBCode\Output\BBCodeOutputInterface
	 */
	protected $outputInterface;

	/**
	 * @var \chillerlan\BBCode\ParserMiddlewareInterface
	 */
	protected $parserMiddleware;

	/**
	 * @var array
	 */
	protected $tags = [];

	/**
	 * @var array
	 */
	protected $noparse = [];

	/**
	 * @var array
	 */
	protected $allowed = [];

	/**
	 * BBCode constructor.
	 *
	 * @param \chillerlan\Traits\ContainerInterface|null $options
	 * @param \Psr\SimpleCache\CacheInterface|null       $cache
	 * @param \Psr\Log\LoggerInterface|null              $logger
	 */
	public function __construct(ContainerInterface $options = null, CacheInterface $cache = null, LoggerInterface $logger = null){
		$this
			->setCache($cache ?? new BBCache)
			->setLogger($logger ?? new NullLogger);

		$this->setOptions($options ?? new BBCodeOptions);
	}

	/**
	 * @param array $allowedTags
	 *
	 * @return \chillerlan\BBCode\BBCode
	 */
	public function allowTags(array $allowedTags):BBCode{
		$this->allowed = [];

		foreach($allowedTags as $tag){
			$tag = strtolower($tag);

			if(in_array($tag, $this->tags, true)){
				$this->allowed[] = $tag;
			}
		}

		return $this;
	}

	/**
	 * @param \Psr\SimpleCache\CacheInterface $cache
	 *
	 * @return \chillerlan\BBCode\BBCode
	 */
	public function setCache(CacheInterface $cache):BBCode{
		$this->cache = $cache;

		return $this;
	}

	/**
	 * @todo
	 *
	 * @param \chillerlan\Traits\ContainerInterface $options
	 *
	 * @throws \chillerlan\BBCode\BBCodeException
	 * @return \chillerlan\BBCode\BBCode
	 */
	public function setOptions(ContainerInterface $options):BBCode{
		$this->options = $options;

		mb_internal_encoding('UTF-8');

		if(
			ini_set('pcre.backtrack_limit', $this->options->pcre_backtrack_limit) === false
			|| ini_set('pcre.recursion_limit', $this->options->pcre_recursion_limit) === false
			|| ini_set('pcre.jit', $this->options->pcre_jit) === false
		){
			throw new BBCodeException('could not alter ini settings');
		}

		if(ini_get('pcre.backtrack_limit') !== (string)$this->options->pcre_backtrack_limit
			|| ini_get('pcre.recursion_limit') !== (string)$this->options->pcre_recursion_limit
			|| ini_get('pcre.jit') !== (string)$this->options->pcre_jit
		){
			throw new BBCodeException('ini settings differ from options');
		}

		if($this->options->sanitizeInput || $this->options->sanitizeOutput){
			$this->sanitizerInterface  = $this->loadClass($this->options->sanitizerInterface, SanitizerInterface::class, $this->options);
		}



		if($this->options->preParse || $this->options->postParse){
			$this->parserMiddleware = $this->loadClass($this->options->parserMiddlewareInterface, ParserMiddlewareInterface::class, $this->options, $this->cache, $this->logger);
		}

		$this->outputInterface = $this->loadClass($this->options->outputInterface, BBCodeOutputInterface::class, $this->options, $this->cache, $this->logger);

		$this->tags    = $this->outputInterface->getTags();
		$this->noparse = $this->outputInterface->getNoparse();

		if(is_array($this->options->allowedTags) && !empty($this->options->allowedTags)){
			$this->allowTags($this->options->allowedTags);
		}
		elseif($this->options->allowAvailableTags === true){
			$this->allowed = $this->tags;
		}

		return $this;
	}

	/**
	 * Transforms a BBCode string to HTML (or whatevs)
	 *
	 * @param string $bbcode
	 *
	 * @return string
	 */
	public function parse(string $bbcode):string{

		// sanitize the input if needed
		if($this->options->sanitizeInput){
			$bbcode = $this->sanitizerInterface->sanitizeInput($bbcode);
		}

		// run the pre-parser
		if($this->options->preParse){
			$bbcode = $this->parserMiddleware->pre($bbcode);
		}

		// @todo: array < 2 elements causes a PREG_BACKTRACK_LIMIT_ERROR! (breaks match pattern)
		$singleTags = array_merge(['br', 'hr'], $this->outputInterface->getSingleTags());

		// close singletags: [br] -> [br][/br]
		$bbcode = preg_replace('#\[('.implode('|', $singleTags).')((?:\s|=)[^]]*)?]#is', '[$1$2][/$1]', $bbcode);
		// protect newlines
		$bbcode = str_replace(["\r", "\n"], ['', $this->options->placeholder_eol], $bbcode);
		// parse the bbcode
		$bbcode = $this->parseBBCode($bbcode);

		// run the post-parser
		if($this->options->postParse){
			$bbcode = $this->parserMiddleware->post($bbcode);
		}

		// replace the newline placeholders
		$bbcode = str_replace($this->options->placeholder_eol, PHP_EOL, $bbcode);

		// run the sanitizer/html purifier/whatever as a final step
		if($this->options->sanitizeOutput){
			$bbcode = $this->sanitizerInterface->sanitizeOutput($bbcode);
		}

		return $bbcode;
	}

	/**
	 * @param $bbcode
	 *
	 * @return string
	 */
	protected function parseBBCode($bbcode):string{
		static $callback_count = 0;

		$callback = false;

		if(is_array($bbcode) && count($bbcode) === 4){
			[$match, $tag, $attributes, $content] = $bbcode;

			$tag        = strtolower($tag);
			$attributes = $this->parseAttributes($attributes);
			$callback   = true;

			$callback_count++;
		}
		else if(is_string($bbcode) && !empty($bbcode)){
			$match      = null;
			$tag        = null;
			$attributes = [];
			$content    = $bbcode;
		}
		else{
			return '';
		}

		if($callback_count < (int)$this->options->nestingLimit && !in_array($tag, $this->noparse , true)){
			$content = preg_replace_callback('#\[(\w+)((?:\s|=)[^]]*)?]((?:[^[]|\[(?!/?\1((?:\s|=)[^]]*)?])|(?R))*)\[/\1]#', __METHOD__, $content);
			$e = preg_last_error();

			/**
			 * 1 - PREG_INTERNAL_ERROR
			 * 2 - PREG_BACKTRACK_LIMIT_ERROR
			 * 3 - PREG_RECURSION_LIMIT_ERROR
			 * 4 - PREG_BAD_UTF8_ERROR
			 * 5 - PREG_BAD_UTF8_OFFSET_ERROR
			 * 6 - PREG_JIT_STACKLIMIT_ERROR
			 */
			if($e !== PREG_NO_ERROR){
				$this->logger->debug('preg_error', ['errno' => $e, '$content' => $content]);

				$content = $match ?? '';//$content ?? $bbcode ??
			}
		}

		if($callback === true && in_array($tag, $this->allowed, true)){
			$content = $this->outputInterface->transform($tag, $attributes, $content, $match, $callback_count);
			$callback_count = 0;
		}

		return $content;
	}

	/**
	 * @param string $attributes
	 *
	 * @return array
	 */
	protected function parseAttributes(string $attributes):array{
		$attr = [];

		if(empty($attributes)){
			return $attr;
		}

		// @todo: fix attributes pattern: accept single and double quotes around the value
		if(preg_match_all('#(?<name>^|[[a-z]+)\=(["\']?)(?<value>[^"\']*?)\2(?: |$)#i', $attributes, $matches, PREG_SET_ORDER) > 0){
#			print_r(['$attributes' => $attributes, '$matches' => $matches]);

			foreach($matches as $attribute){
				$name = empty($attribute['name']) ? $this->options->placeholder_bbtag : strtolower(trim($attribute['name']));

				$attr[$name] = trim($attribute['value'], '"\' ');
			}
		}

		$e = preg_last_error();

		if($e !== PREG_NO_ERROR){
			$this->logger->debug('preg_error', ['errno' => $e, '$attributes' => $attributes]);
			$attr['__error__'] = $attributes;
		}

		return $attr;
	}

}
