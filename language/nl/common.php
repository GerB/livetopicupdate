<?php
/**
 *
 * Live topic update. An extension for the phpBB Forum Software package.
 * [Dutch]
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
    'LTU_CHECK_INTERVAL'            => 'Interval voor live topic update',
    'LTU_CHECK_INTERVAL_EXPLAIN'    => 'Hoe vaak (aantal seconden) er gecontroleerd wordt op nieuwe berichten in een onderwerp. Een lagere waarde zorgt voor frequentere controles maar kan een belasting zijn voor de server.',
    'LTU_INVALID_QUERY'             => 'Ongeldig verzoek',
    'LTU_NEW_POSTS'                 => array(
        1	=> 'Er is %d nieuw bericht in dit onderwerp',
        2	=> 'Er zijn %d nieuwe berichten in dit onderwerp',
	),
));
