<?php
/**
*
* @author michaelo phpbbireland@gmail.com - http://www.phpbbireland.com
*
* @package form_maker
* @version 1.0.0
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

include($phpbb_root_path . 'install_phpbb_form_mod/sql_data.' . $phpEx);

$versions = array(

	// Version 1.0.0
	'1.0.0' => array(
		'permission_add' => array(
			array('a_form_maker', true),
		),

		'permission_set' => array(
			array('ROLE_ADMIN_FULL', 'a_form_maker'),
		),

		'config_add' => array(
			array('form_maker_enabled', 1),
		),

		'table_add' => array(
			array('phpbb_form_maker', array(
					'COLUMNS' => array(
						'id'				=> array('UINT', NULL, 'auto_increment'),
						'form_id'			=> array('UINT', '0'),
						'ndx_order'			=> array('UINT', '0'),
						'name'				=> array('VCHAR', ''),
						'type'				=> array('VCHAR', ''),
						'hint'				=> array('VCHAR', ''),
						'options'			=> array('VCHAR', ''),
						'mandatory'			=> array('BOOL', '0'),
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),

		),

		'module_add' => array(
			array('acp', 'ACP_CAT_DOT_MODS', 'FORM_MAKER'),

			array('acp', 'FORM_MAKER', array(
					'module_basename'	=> 'form_maker',
					'module_langname'	=> 'ACP_FORM_MAKER',
					'module_mode'		=> 'manage',
					'module_auth'		=> 'acl_a_form_maker',
				),
			),
		),

		'table_insert' => array(
			array($form_maker_table, $form_maker_array),
		),

		// purge the cache
		'cache_purge' => array('', 'imageset', 'template', 'theme'),
	),

);//version

include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>
