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
	protected $phpbb_root_path;
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper    $helper        Controller helper object
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper,  \phpbb\user $user, \phpbb\db\driver\driver_interface $db, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->user = $user;
		$this->db = $db;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_user_add'	=> 'add_user',
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
			$data = array('new_username' => utf8_normalize_nfc(request_var('username', '', true)));
			$new_password = str_split(base64_encode(md5(time() . $data['new_username'])), $this->config['min_pass_chars'] + rand(3, 5));
			$data['new_password'] = $new_password[0];
			$sql = 'SELECT group_id
					FROM ' . GROUPS_TABLE . "
					WHERE group_name = 'REGISTERED' AND group_type = " . GROUP_SPECIAL;
			$result = $this->db->sql_query($sql);
			$group_id = $this->db->sql_fetchfield('group_id');
			$this->db->sql_freeresult($result);

			$user_row = array(
				'username'				=> utf8_normalize_nfc(request_var('username', '', true)),
				'user_password'			=> md5($data['new_password']),
				'user_email'			=> '',
				'group_id'				=> (int) $group_id,
				'user_type'				=> USER_NORMAL,
				'user_ip'				=> $this->user->ip,
				'user_regdate'			=> time(),
			);
			$user_id = user_add($user_row);
			$event['user_id'] = $user_id;
			$event['trigger_override'] = true;
			add_log('admin', 'LOG_USER_ADDED', $data['new_username']);
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
}
