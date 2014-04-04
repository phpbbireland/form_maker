<?php
/**
 * Form template generator
 * @package form_maker
 * @link http://www.phpbbireland.com
 * @author phpbbireland@gmail.com
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0.0
 */


/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/
class acp_form_maker_info
{
	function module()
	{
		return array(
			'filename' => 'acp_form_maker',
			'title'    => 'ACP_FORM_MAKER',
			'version'  => '1.0.0',
			'modes'    => array(
				'manage' => array('title' => 'ACP_FORM_MAKER', 'display' => 1, 'auth'    => 'acl_a_form_maker', 'cat' => array('ACP_FORM_MAKER')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}


?>
