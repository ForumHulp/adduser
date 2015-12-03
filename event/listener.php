<?php
/**
*
* @package Add user
* @copyright (c) 2014 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\adduser\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	protected $config;
	/* @var \phpbb\controller\helper */
	protected $helper;

	protected $user;

	protected $db;
	protected $log;
	protected $phpbb_root_path;
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper    $helper        Controller helper object
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\log\log $log, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->user = $user;
		$this->db = $db;
		$this->log				= $log;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_user_add'			=> 'add_user',
			'core.user_add_modify_data'	=> 'change_email'
		);
	}

	public function add_user($event)
	{
		if (confirm_box(true))
		{
			if (!function_exists('user_add'))
			{
				include_once($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
			}

			$sql = 'SELECT group_id FROM ' . GROUPS_TABLE . " WHERE group_name = 'REGISTERED' AND group_type = " . GROUP_SPECIAL;
			$result = $this->db->sql_query($sql);
			$group_id = $this->db->sql_fetchfield('group_id');
			$this->db->sql_freeresult($result);

			$user_row = array(
				'username'				=> utf8_normalize_nfc($event['username']),
				'user_password'			=> '',
				'user_email'			=> 'adduser@' . $this->config['server_name'],
				'group_id'				=> (int) $group_id,
				'user_type'				=> USER_NORMAL,
				'user_ip'				=> $this->user->ip,
				'user_regdate'			=> time(),
			);
			$user_id = user_add($user_row);
			$event['user_id'] = $user_id;
			$event['trigger_override'] = true;
			$this->log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_USER_ADDED', false, array($user_row['username']));
		} else
		{
			$username = $event['username'];
			$id = $event['id'];
			$mode = $event['mode'];

			confirm_box(false, $this->user->lang('ADD_USER', $username), build_hidden_fields(array(
				'i'					=> $id,
				'mode'				=> $mode,
				'username'			=> $username,
				'action'			=> 'add_user',
			)));
		}
	}

	public function change_email($event)
	{
		$sql_ary = $event['sql_ary'];
		$sql_ary['user_email'] = '';
		$event['sql_ary'] = $sql_ary;
	}
}
