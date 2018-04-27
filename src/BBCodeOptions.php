<?php
/**
 * Class BBCodeOptions
 *
 * @filesource   BBCodeOptions.php
 * @created      19.04.2018
 * @package      chillerlan\BBCode
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode;

use chillerlan\Traits\ContainerAbstract;

/**
 * @property string $sanitizerInterface
 * @property string $outputInterface
 * @property string $parserMiddlewareInterface
 * @property bool   $sanitizeInput
 * @property bool   $sanitizeOutput
 * @property bool   $preParse
 * @property bool   $postParse
 * @property int    $nestingLimit
 * @property int    $pcre_backtrack_limit
 * @property int    $pcre_recursion_limit
 * @property int    $pcre_jit
 * @property string $placeholder_eol
 * @property string $placeholder_bbtag
 * @property array  $allowedTags
 * @property bool   $allowAvailableTags
 */
class BBCodeOptions extends ContainerAbstract{
	use BBCodeOptionsTrait;
}
