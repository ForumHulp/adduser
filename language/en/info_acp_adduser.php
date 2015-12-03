<?php
/**
*
* @package Add user
* @copyright (c) 2014 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'ADD_USER'				=> 'Add new user <strong>%s</strong>?',
	'LOG_USER_ADDED'		=> '<strong>New user added</strong><br />» %s',
	'ADD_USER_NOTICE'		=> '<div class="phpinfo"><p class="entry">This extension is not vissible nor adjustable. Once you enter a username in manage users it wil ask you if the username should be added if it can not find it. After this it will jump to manage the new user.</p></div>',
	'FH_HELPER_NOTICE'		=> 'Forumhulp helper application does not exist!<br />Download <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> and copy the helper folder to your forumhulp extension folder.',
));
