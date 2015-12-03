<?php
/**
*
* @package Add user
* @copyright (c) 2014 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\adduser\migrations\v31x;

use phpbb\db\migration\container_aware_migration;

/**
 * Migration stage 1: Initial schema
 */
class m1_initial_file extends container_aware_migration
{
	/**
	 * Assign migration file dependencies for this migration
	 *
	 * @return array Array of migration files
	 * @static
	 * @access public
	 */
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\gold');
	}

	/**
	* Add core change to the files.
	*
	* @return array Array of files
	* @access public
	*/
	public function update_data()
	{
		$this->revert = false;
		return array(
			array('custom', array(array($this, 'update_files'))),
		);
	}

	public function revert_data()
	{
		$this->revert = true;
		return array(
			array('custom', array(array($this, 'update_files'))),
		);
	}

	public function update_files()
	{
		if (class_exists('forumhulp\helper\helper'))
		{
			if (!$this->container->has('forumhulp.helper'))
			{
				$forumhulp_helper = new \forumhulp\helper\helper(
					$this->config,
					$this->container->get('ext.manager'),
					$this->container->get('template'),
					$this->container->get('user'),
					$this->container->get('request'),
					$this->container->get('log'),
					$this->container->get('cache'),
					$this->phpbb_root_path
				);
				$this->container->set('forumhulp.helper', $forumhulp_helper);
			}
			$this->container->get('forumhulp.helper')->update_files($this->data(), $this->revert);
		} else
		{
			$this->container->get('user')->add_lang_ext('forumhulp/adduser', 'info_acp_adduser');
			trigger_error($this->container->get('user')->lang['FH_HELPER_NOTICE'], E_USER_WARNING);
		}
	}

	public function data()
	{
		$replacements = array(
			'files' => array(
				0 => '/includes/acp/acp_users.' . $this->php_ext,
				),
			'searches' => array(
				0 => array(
					0 => '			if (!$user_id)
			{
				trigger_error($user->lang[\'NO_USER\'] . adm_back_link($this->u_action), E_USER_WARNING);
			}'
					)
				),
			'replaces' => array(
				0 => array(
					0 => '			if (!$user_id)
			{
				$trigger_override = false;

				/**
				* Run add user code
				*
				* @event core.acp_user_add
				* @var	string	username				Username to search for
				* @var	int		id						Module id to search for
				* @var	string	mode					Module mode
				* @var	bool	trigger_override		Override not found
				* @since 3.1.0-a3
				*/
				$vars = array(\'username\', \'user_id\', \'id\', \'mode\', \'trigger_override\');
				extract($phpbb_dispatcher->trigger_event(\'core.acp_user_add\', compact($vars)));

				if ($trigger_override)
				{
					$user_id = $user_id;
				} else
				{
					trigger_error($user->lang[\'NO_USER\'] . adm_back_link($this->u_action), E_USER_WARNING);
				}
			}',
					)
				)
			);
		return $replacements;
	}
}
