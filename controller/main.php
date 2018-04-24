<?php
/**
 *
 * Live topic update. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\livetopicupdate\controller;

use phpbb\json_response;
/**
 * Live topic update main controller.
 */
class main
{
	/* @var \phpbb\config\config */
	protected $request;

	/* @var \phpbb\controller\helper */
	protected $db;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\user */
	protected $auth;

	/* @var \phpbb\content_visibility */
	protected $content_visibility;

	/* @var \phpbb\lang */
	protected $lang;

    /**
     * Controller constructor
     *
     * @access public
     * @since  1.0.0
     *
     * @param request    $request    Request object
     * @param factory    $db         Database object
     * @param user       $user       User object
     * @param auth	 $auth	     Auth object
     * @param lang	 $lang	     Language object
     */
    public function __construct(\phpbb\request\request_interface $request, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\content_visibility $content_visibility, \phpbb\language\language $lang)
    {
        $this->request = $request;
        $this->db = $db;
        $this->user = $user;
        $this->auth = $auth;
        $this->content_visibility = $content_visibility;
        $this->lang = $lang;
    }

    /**
     * Check if new posts are made
     */
    public function handle($tid, $old)
    {
        $response = new json_response();
        $response_text[] = 0;
        if ($this->request->is_ajax())
        {
            if ($tid > 0) 
            {
                $current = $this->get_topic_count($tid);
                $langstring = 'LTU_NEW_POSTS';
            }
            else 
            {
                if ($old === 1000 || empty($this->user->data['is_registered']))
                {
                    // Hard limit set in search.php. We can't update in a reliable way
                    $current = $old;
                } 
                else
                {
                    $current = $this->get_unread_count();
                    $langstring = 'LTU_NEW_TOPICS';
                }
            }
            $diff = (int) $current - (int) $old;
            if ($diff > 0) 
            {
                $this->lang->add_lang('common', 'ger/livetopicupdate');
                $response_text['ltu_yes'] = $this->user->lang($langstring, $diff);
                $response_text['ltu_nr'] = $diff;
            }
        }
        $response->send($response_text);
    }
    
    
    /**
     * Get total count
     * @return int
     */
    private function get_unread_count()
    {
        // Check for unread topics
        $no_permission = array_keys($this->auth->acl_getf('!f_read', true));
        $m_approve_topics_fid_sql = $this->content_visibility->get_global_visibility_sql('topic', $no_permission, 't.');
        $sql_extra = 'AND t.topic_moved_id = 0 AND ' . $m_approve_topics_fid_sql;
        if ($no_permission) 
        {
            $sql_extra.= ' AND ' . $this->db->sql_in_set('t.forum_id', $no_permission, true);
        }
        return count(get_unread_topics($this->user->data['user_id'], $sql_extra));
    }
    
    
    /**
     * Get current topic count
     * @param int $tid
     * @return int
     */
    private function get_topic_count($tid)
    {
        $sql = 'SELECT forum_id, topic_posts_approved, topic_posts_unapproved, topic_posts_softdeleted
             FROM ' . TOPICS_TABLE . "
                 WHERE topic_id = " . (int) $tid;
        $result = $this->db->sql_query($sql);
        $data = $this->db->sql_fetchrow($result);

        if (!$this->auth->acl_get('m_approve', $data['forum_id']))
        {
            return (int) $data['topic_posts_approved'];
        }
        return (int) $data['topic_posts_approved'] + (int) $data['topic_posts_unapproved'] + (int) $data['topic_posts_softdeleted'];
    }
}
