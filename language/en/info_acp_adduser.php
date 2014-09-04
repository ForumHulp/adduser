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
	'ADD_USER'			=> 'Add new user <strong>%s</strong>?',
	'LOG_USER_ADDED'	=> '<strong>New user added</strong><br />» %s',
));
