<?php
/**
 *
 * Live topic update. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\livetopicupdate\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Live topic update Event listener.
 */
class main_listener implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
	return array(
	    'core.viewtopic_assign_template_vars_before'	=> 'assign_template_vars',
	);
    }

    /* @var \phpbb\controller\helper */
    protected $helper;

    /* @var \phpbb\template\template */
    protected $template;

    /* @var \phpbb\user */
    protected $user;

    /** @var string phpbb_root_path */
    protected $phpbb_root_path;

    /** @var string phpEx */
    protected $php_ext;

    /**
     * Constructor
     *
     * @param \phpbb\controller\helper	$helper		    Controller helper object
     * @param \phpbb\template\template	$template	    Template object
     * @param \phpbb\user               $user		    User object
     * @param string                    $phpbb_root_path
     * @param string                    $php_ext
     */
    public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, $php_ext)
    {
	$this->helper		= $helper;
	$this->template	        = $template;
	$this->user		= $user;
	$this->phpbb_root_path  = $phpbb_root_path;
	$this->php_ext		= $php_ext;
    }

    /**
     * Send reuired vars to template
     */
    public function assign_template_vars($event)
    {
	$this->template->assign_vars(array(
	    'U_LIVEUPDATE'	=> $this->helper->route('ger_livetopicupdate_controller', ['tid' => $event['topic_id'], 'old' => $event['total_posts']]),
	    'U_REFRESH'		=> append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext . '?f=' . $event['forum_id'] . '&t=' . $event['topic_id'] . '&view=unread#unread'),
	));
    }
}