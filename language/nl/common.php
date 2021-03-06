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
    'LTU_CHECK_INTERVAL'            => 'Interval voor live update',
    'LTU_CHECK_INTERVAL_EXPLAIN'    => 'Hoe vaak (in secondes) er gecontroleerd wordt op nieuwe berichten in een onderwerp. Een lagere waarde zorgt voor frequentere controles maar kan een belasting zijn voor de server.',
    'LTU_SEARCH_PAGE'               => 'Live update zoekfunctie',
    'LTU_SEARCH_PAGE_EXPLAIN'       => 'Controleert op nieuwe onderwerpen op de zoekpagina voor ongelezen berichten.',
    'LTU_INVALID_QUERY'             => 'Ongeldig verzoek',
    'LTU_NEW_POSTS'                 => array(
        1	=> 'Er is %d nieuw bericht in dit onderwerp',
        2	=> 'Er zijn %d nieuwe berichten in dit onderwerp',
	),
    'LTU_NEW_TOPICS'                 => array(
        1	=> 'Er is %d nieuw onderwerp geplaatst',
        2	=> 'Er zijn %d nieuwe onderwerpen geplaatst',
	),
    'LTU_REFRESH'                    => 'Klik om te tonen',
));
