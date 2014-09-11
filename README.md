Add user
===========

Add user gives the admin the possibility to add a new user in ACP. After adding you can manage the new user as you are used in user management.

[![Build Status](https://travis-ci.org/ForumHulp/adduser.svg?branch=master)](https://travis-ci.org/ForumHulp/adduser)

## Requirements
* phpBB 3.1-dev or higher
* PHP 5.3 or higher

## Installation
You can install this on the latest copy of the develop branch ([phpBB 3.1-dev](https://github.com/phpbb/phpbb3)) by doing the following:

1. Copy the entire contents of this repo to to `FORUM_DIRECTORY/ext/forumhulp/adduser/`
2. Add a event in acp_users.php after if (!$user_id)
3. {		// A listener can set this variable to `true` when it overrides this function
				$trigger_override = false;
				/**
				* Run Add user
				*
				* @event core.acp_user_add
				* @var	int		id					Module id
				* @var	string	mode				Module mode
				* @var	string	username			username
				* @var	int		user_id				user_id
				* @var	bool	trigger_override	Shall we return to normal operations
				* @since 3.1.0-RC4
				*/
				$vars = array('id', 'mode', 'username', 'user_id', 'trigger_override');
				extract($phpbb_dispatcher->trigger_event('core.acp_user_add', compact($vars)));

4. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
5. Click Add user => `Enable`.

Note: This extension is in development. Installation is only recommended for testing purposes and is not supported on live boards. This extension will be officially released following phpBB 3.1.0. Extension depends on two core changes.

## Uninstallation
Navigate in the ACP to `Customise -> Extension Management -> Extensions` and click Add user => `Disable`.

To permanently uninstall, click `Delete Data` and then you can safely delete the `/ext/forumhulp/adduser/` folder.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2014 - John Peskens (ForumHulp.com)
