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
    public function __construct(\phpbb\request\request_interface $request, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\language\language $lang)
    {
        $this->request = $request;
        $this->db = $db;
        $this->user = $user;
        $this->auth = $auth;
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
	    $current = $this->get_topic_count($tid);
	    $diff = (int) $current - (int) $old;
	    if ($diff > 0) 
	    {
		$this->lang->add_lang('common', 'ger/livetopicupdate');
		$response_text['ltu_yes'] = $this->user->lang('LTU_NEW_POSTS', $diff);
	    }
	}
	$response->send($response_text);
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
