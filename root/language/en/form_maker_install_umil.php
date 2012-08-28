<?php
/**
*
* @package install
* @version $Id: form_maker_install_umil.php 0.0.1
* @copyright (c) 2012 phpbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine


$lang = array_merge($lang, array(

	'KISS_PORTAL_ENGINE' 		=> 'Kiss Portal Engine',
	'STARGATE_PORTAL_EXPLAIN' 	=> 'Simplified version of Stargate Portal for phpBB3 with a basic features all of which are configurable via the ACP.',

	'NONE'				=> 'Not Installed',
	'INSTALL_PANEL'		=> 'Form Maker Installation Panel',
	'SUB_INTRO'			=> 'Introduction',
	'OVERVIEW_BODY'		=> '',
	'SELECT_LANG'		=> 'Select language',
	'SUB_SUPPORT'		=> 'Support',
	'REPORT_INSTALLED'	=> 'The Form Maker Mod in already installed',
	'INSTALL_INTRO'		=> 'Welcome to the Form Maker Installation <img src="./../form_makler_install.png" alt="" border="none">',


	'VERSION_NOT_UP_TO_DATE'	=> 'Your version of the form maker is not up to date. Please continue the update process.',
	'VERSION_NOT_UP_TO_DATE'	=> 'Cannot retrieve version info... code not yet written.',
	'VERSION_CHECK'				=> 'Version check',
	'VERSION_CHECK_EXPLAIN'		=> 'Checks to see if Form Maker version you are currently running is up to date.',
	'CURRENT_VERSION'			=> 'Current version',
	'LATEST_VERSION'			=> 'Latest version',

));

?>