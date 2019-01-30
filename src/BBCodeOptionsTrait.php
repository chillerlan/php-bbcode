<?php
/**
 * Trait BBCodeOptionsTrait
 *
 * @filesource   BBCodeOptionsTrait.php
 * @created      19.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

use chillerlan\BBCode\Output\HTML\{HTMLOutput, HTMLSanitizer};

trait BBCodeOptionsTrait{

	/**
	 * FQCN of the output module
	 *
	 * @var string
	 */
	protected $outputInterface = HTMLOutput::class;

	/**
	 * FQCN of the sanitizer interface
	 *
	 * @var string
	 */
	protected $sanitizerInterface = HTMLSanitizer::class;

	/**
	 * Input sanitizing
	 *
	 * @var bool
	 */
	protected $sanitizeInput = true;

	/**
	 * Output sanitizing
	 *
	 * @var bool
	 */
	protected $sanitizeOutput = false;

	/**
	 * FQCN of the parser middleware
	 *
	 * @var string
	 */
	protected $parserMiddlewareInterface = null;

	/**
	 * Run the pre-parser
	 *
	 * @var bool
	 */
	protected $preParse = false;

	/**
	 * Run the post-parser
	 *
	 * @var bool
	 */
	protected $postParse = false;

	/**
	 * Nesting limit
	 *
	 * Note: setting this value too high may either lock up your CPU for a while or crash PHP.
	 *       100 is plenty (imagine quote wars!).
	 *
	 * @var int
	 */
	protected $nestingLimit = 10;

	/**
	 * enable for segfaults!
	 *
	 * @var int
	 */
	protected $pcre_jit = 0;

	/**
	 * @var int
	 */
	protected $pcre_backtrack_limit = 2000000;

	/**
	 * @var int
	 */
	protected $pcre_recursion_limit = 200000;

	/**
	 * The EOL placeholder token
	 *
	 * @var string
	 */
	protected $placeholder_eol = '{EOL}';

	/**
	 * optional EOL replacement
	 *
	 * @var string
	 */
	protected $replacement_eol = null;

	/**
	 * The key name of the tag attribute
	 *
	 * [bbtag=val ...]
	 *
	 * @var string
	 */
	protected $placeholder_bbtag = '{TAG_ATTR}';

	/**
	 * @var array
	 */
	protected $allowedTags = [];

	/**
	 * @var bool
	 */
	protected $allowAvailableTags = true;

}
