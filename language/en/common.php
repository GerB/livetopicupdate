<?php
/**
 *
 * Live topic update. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
    'LTU_CHECK_INTERVAL'            => 'Interval for live topic update',
    'LTU_CHECK_INTERVAL_EXPLAIN'    => 'How frequently (in seconds) the board checks for new posts in a topic. A lower value means more frequent updates but may be stressful for the server.',
    'LTU_INVALID_QUERY'             => 'Invalid request',
    'LTU_NEW_POSTS'                 => array(
		1	=> 'There is %d new post in this topic',
		2	=> 'There are %d new posts in this topic',
	),
    'LTU_REFRESH'                    => 'Click to show',
));
