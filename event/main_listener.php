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
            'core.viewtopic_modify_page_title'              => 'check_last_page',
            'core.acp_board_config_edit_add'                => 'acp_add_check_interval'
        );
    }

    /* post counter */
    private $post_count = 0;

    /* @var \phpbb\controller\helper */
    protected $helper;

    /* @var \phpbb\template\template */
    protected $template;

    /* @var \phpbb\config\config */
    protected $config;

    /* @var \phpbb\language\language */
    protected $lang;

    /** @var string phpbb_root_path */
    protected $phpbb_root_path;

    /** @var string phpEx */
    protected $php_ext;

    /**
     * Constructor
     *
     * @param \phpbb\controller\helper	$helper		    Controller helper object
     * @param \phpbb\template\template	$template	    Template object
     * @param \phpbb\config\config      $config		    Config object
     * @param \phpbb\language\language  $lang		    Language object
     * @param string                    $phpbb_root_path
     * @param string                    $php_ext
     */
    public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\config\config $config, \phpbb\language\language $lang, $phpbb_root_path, $php_ext)
    {
        $this->helper           = $helper;
        $this->template	        = $template;
        $this->config           = $config;
        $this->lang             = $lang;
        $this->phpbb_root_path  = $phpbb_root_path;
        $this->php_ext          = $php_ext;
    }

    /**
     * Add ACP option to modify the interval
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function acp_add_check_interval($event)
    {
        if ($event['mode'] == 'load')
        {
            $this->lang->add_lang('common', 'ger/livetopicupdate');
            $display_vars = $event['display_vars'];            
            
            $add = array ('ger_livetopicupdate_interval'  => array('lang' => 'LTU_CHECK_INTERVAL',	'validate' => 'int:0',	'type' => 'number:0:99999', 'explain' => true));
            $position = array_search('read_notification_expire_days', array_keys($display_vars['vars'])) + 1;
            $display_vars['vars'] = array_merge(
                array_slice($display_vars['vars'], 0, $position),
                $add,
                array_slice($display_vars['vars'], $position)
            );

            $event['display_vars'] = $display_vars;
        }
    }
    
    /**
     * Send reuired vars to template
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function assign_template_vars($event)
    {
        $this->post_count = (int) $event['total_posts'];
        
        $this->template->assign_vars(array(
            'U_LIVEUPDATE'      => $this->helper->route('ger_livetopicupdate_controller', ['tid' => $event['topic_id'], 'old' => $event['total_posts']]),
            'U_REFRESH'         => append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext . '?f=' . $event['forum_id'] . '&t=' . $event['topic_id'] . '&view=unread#unread'),
            'S_LTU_INTERVAL'	=> (int) $this->config['ger_livetopicupdate_interval'],
        ));
    }
    
    /**
     * Check if we are one the last page, no need to live update if on earlier page
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function check_last_page($event)
    {
        $on_last_page = ((floor($event['start'] / $this->config['posts_per_page']) + 1) == max(ceil($this->post_count / $this->config['posts_per_page']), 1)) ? true : false;
        $this->template->assign_vars(array(
            'S_LIVE_UPDATE'	=> $on_last_page,
        ));
    }
}