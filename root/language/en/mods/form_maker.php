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
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

/**
* DO NOT CHANGE
*/
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

/*  here you change the fixed strings  for the recruitment page */

$lang = array_merge($lang, array(

'ACP_FORM_CURRENT'         => 'Current form',
'ACP_FORM_MAKER'           => 'Form Maker',
'ACP_FORM_MAKER_EXPLAIN'   => 'This tool manages forms you create for use with your forums (form’s id is used to automatically assign a form to a forum)...<br /><br />
<strong>Select a Form to work with:</strong> Select the forum from the dropdown box and/or click the Update Button...<br /><br />
<strong>Add A New Form:</strong> Select the forum from the dropdown box, then use: Add a new form element to this form...<br />
<strong>To delete a Form:</strong> Simply delete all form elements for that form...<br /><br />

<strong>Note:</strong> All actions are performed on the currently displayed form. To speed up actions, no confirm is required so be careful...',

'FORM_ADD_ITEM'            => 'Add a new form element to this form',
'FORM_CHECKBOX'            => 'Check Box',
//'FORM_DETAILS'             => 'Form Details: %1$s %2$s',
'FORM_DETAILS'             => 'All forms use a generic template file: styles/prosilver/template/forms/form_maker.html',
'FORM_ELEMENT'             => 'Input Type',
'FORM_ELEMENT_TYPE'        => 'Element type',
'FORM_ELEMENT_HINT'        => 'Hint',
'FORM_ELEMENT_NAME'        => 'Entry Name',
'FORM_ELEMENT_OPTIONS'     => 'Options',
'FORM_INPUTBOX'            => 'Text',
'FORM_MAKER_ACP_DELETED'   => 'Entry deleted',
'FORM_MAKER_ACP_ERROR'     => 'Error updating form maker database',
'FORM_MAKER_ACP_MOVED'     => 'Move completed...',
'FORM_MAKER_ACP_RETURN'    => 'Back to manage forms',
'FORM_MANAGE'              => 'Manage this form',
'FORM_MANAGE_EXPLAIN'      => 'Here you can modify (add/edit) form elements...',
'FORUM_NAME'               => 'Forum Name',
'FORM_NEW_ITEM_EXPLAIN_1'  => 'Here you can add additional elements to existing forms or create a new form by adding an element...',
'FORM_NEW_ITEM_EXPLAIN_2'  => '<strong>Inputbox</strong> (up to 255 characters)<br /><strong>Textbox</strong> (multiple lines of text arranged as 3 rows by 76 columns)<br /><strong>Checkbox</strong> (one or more options can be checked)<br /><strong>Radiobutton</strong> (only one option can be selected)<br /><strong>Selectbox</strong> (a dropdown list)<br />Check the &#8730; column to indicate the item is mandatory and cannot be empty...',
'FORM_NO_FORM'             => 'There are no forms assigned to: <strong>%s</strong>...<br />To add a new form to this forum, simply add a form element below...',
'FORM_RADIOBOX'            => 'Radio Buttons',
'FORM_SELECTBOX'           => 'Select Box',
'FORM_SELECT_DB'           => 'Available forms',
'FORM_TXTBOX'              => 'Text Box',
'FORUM_TO_USE'             => 'Select forum',
'HIDE_FORM_INFO'           => 'Hide info',
'HIDE_PREVIEW'             => 'Hide Preview',
'MANDATORY'                => 'Items marked with the asterisk are mandatory (they cannot be empty)...',
'NDX'                      => 'NDX',
'NDX_ORDER'                => 'Index order (the order in which items appear on the form)',
'NO_FOURM'                 => 'Bo forum associated with this form',
'SELECT_FORM_TO_MANAGE'    => 'Please select a forum to associate with for this form',
'SHOW_FORM_INFO'           => 'More info on Element types',
'SHOW_PREVIEW'             => 'Preview the form',

'CLOSE_FORM'      => 'Post Mode',
'OPEN_FORM'       => 'Form Mode',
'CLOSE_FORM_EXPLAIN'  => 'Switch to Post Mode, copying Form data to the post',
'FORM_HELP_1'         => 'Editing using <strong>Form Mode</strong> is not yet written...',
'OPEN_FORM_EXPLAIN'   => 'Swicth to Form Mode...',
'REFRESHING_FORM'     => 'Form will update onchange...',
));

?>