<?php
/**
*
* @author michaelo phpbbireland@gmail.com - http://www.phpbbireland.com
*
* @package form_maker
* @version 0.0.1
* @copyright (c) 2012 Michael O'Toole (phpbbireland.com)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);

// correct root for poral as we install using root/portal/index.php //

$phpbb_root_path = './../';

$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'FORM_MAKER';

$version_config_name = 'form_maker_version';
$language_file = 'form_maker_install_umil';
$logo_img = 'form_maker_install.png';

include($phpbb_root_path . 'form_maker_install/sql_data.' . $phpEx);

$versions = array(

	// Version 0.0.1
	'0.0.1' => array(
		'permission_add' => array(
			array('a_form_maker', 1),
		),

		'permission_set' => array(
			array('ROLE_ADMIN_FULL', 'a_form_maker'),
		),

		'table_add' => array(
			array('phpbb_form_maker', array(
					'COLUMNS' => array(
						'id'				=> array('UINT', NULL, 'auto_increment'),
						'form_id'			=> array('UINT', '0'),
						'ndx_order'			=> array('UINT', '0'),
						'name'				=> array('VCHAR:100', ''),
						'type'				=> array('VCHAR:50', ''),
						'hint'				=> array('VCHAR', ''),
						'options'			=> array('VCHAR', ''),
						'mandatory'			=> array('BOOL', '1'),
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),

		),

		'table_insert' => array(
			array($form_maker_table, $form_maker_array),
		),

		// purge all cache
		'cache_purge' => array(),
	),

);//version



include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>
