<?php
/**
*
* @package phpBB3 Form Mod
* @version $Id$
* @copyright (c) 20012 phpbbireland.com (michaelo)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Sections of this code are taken form the appform mod 1.0 by Sajaki
* @link http://www.bbdkp.com
* @author Sajaki@gmail.com
*/

/*
* form element types
* textbox
* textarea
* checkbox
* radiobutton
* selectbox
* password
* email
* url
* file
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
*
*
*/

if (!function_exists('build_form'))
{
	function build_form($forum_id)
	{
		global $db, $template, $mode;

		$style = 'style="border-radius: 5px;"';
		$style_ta = 'style="border-radius: 5px; max-width: 400px;"';
		$entry = "";

		$sql = 'SELECT id, form_id, ndx_order, name, hint, type, mandatory, options, forum_name, forum_id
			FROM ' . FORM_MAKER_TABLE . ' m, ' . FORUMS_TABLE . ' f
			WHERE m.form_id = ' . (int)$forum_id . '
			AND m.form_id = f.forum_id
			ORDER BY ndx_order ASC';

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['mandatory'])
			{
				$mandatory = " required ";
			}
			else
			{
				$mandatory = "";
			}

			$temp_name = $row['name'];

			$row['name'] = str_replace(' ', '_', $row['name']);

			// make thigs even easier to read //
			$name = "name='templatefield_{$row['name']}'";
			$id = "id='templatefield_{$row['name']}'";
			$placeholder = "placeholder='{$row['hint']}' ";
			$tabindex = "tabindex='{$row['ndx_order']}' ";

			$type = "type='{$row['type']}' ";

			$size='size="40" ';
			$maxlength = 'maxlength="255" ';
			$cols='rows="3"';
			$rows='cols="76"';

			switch (strtolower($row['type']))
			{
				case 'email':
				case 'password':
				case 'url':
				case 'text':
				case 'file':

					$entry = '<input ' . $type . $name . $id . $placeholder . $mandatory . $size . $maxlength . $tabindex . $style . ' />';

				break;

				case 'textbox':

					$entry = '<textarea ' . $type . $name . $id . $rows . $cols . $tabindex . $placeholder . $mandatory . $style_ta . '" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"></textarea>';
				break;

				case 'selectbox':

					$entry = '<select ' . $type . $name . $id . $tabindex . $mandatory . $style . '>';
					$entry .= '<option value="">----------------</option>';
					$select_option = explode(',', $row['options']);
					foreach($select_option as $value)
					{
						 $entry .='<option value="'. $value .'">'. $value .'</option>';
					}
					$entry .= '</select>';
				break;

				case 'radiobuttons':

					$radio_option = explode(',', $row['options']);
					$entry = '';
					foreach($radio_option as $value)
					{
					   $entry .='<input type="radio" ' . $tabindex . $mandatory . $name . $id . '" value="'. $value .'"/>&nbsp;'. $value . '&nbsp;&nbsp;';
					}
				break;

				case 'checkbox':
					$check_option = explode(',', $row['options']);

					$entry = '';
					foreach($check_option as $value)
					{
					   $entry .='<input ' . $type . $tabindex . $mandatory . ' name="templatefield_' . $row['name'] .'[]"' . $id . '" value="'. $value .'" />&nbsp;'. $value .'&nbsp;&nbsp;';
					}
				break;

				default:
				break;
			}

			$mandatory = '';

			if ($row['mandatory'])
			{
				$mandatory = '<span class="mandatory">*</span>';
			}

			$template->assign_block_vars('form_apptemplate', array(
				'NDX_ORDER' => $row['ndx_order'],
				'NAME'      => $row['name'],
				'LABEL'     => $row['name'],
				'HINT'      => $row['hint'],
				'OPTIONS'   => $row['options'],
				'TYPE'      => $entry,
				'MANDATORY' => $mandatory,
			));

			$template->assign_vars(array(
				'MODE'	=> $mode
			));

		}
		$db->sql_freeresult($result);
	}
}

/**
*	Grab the form data from db and build the form for this forum...
*	Takes the forum id to process...
*	Returns the parsed form (html code)...
*/

if (!function_exists('grab_form_data'))
{
	function grab_form_data($forum_id)
	{
		global $auth, $config, $db, $user, $phpbb_root_path, $phpEx;

		$ret = $appform_post = $last_checked = $temp = '';
		$name_length_max = $file_count = 0;
		$form_data = $names = array();

		$config['title_colour'] = (isset($config['title_colour'])) ? $config['title_colour'] : '#FF0000';

		$sql = 'SELECT *
			FROM ' . FORM_MAKER_TABLE . '
			WHERE form_id = ' . (int)$forum_id . '
			ORDER BY ndx_order ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$form_data[] = array(
				'id'         => $row['id'],
				'form_id'    => $row['form_id'],
				'hint'       => $row['hint'],
				'options'    => $row['options'],
				'mandatory'  => $row['mandatory'],
				'name'       => $row['name'],
				'type'       => $row['type'],
				'ndx_order'  => $row['ndx_order'],
			);

			if ($row['type'] == 'file')
			{
				$files[] = $row['name'];
			}
		}
		$db->sql_freeresult($result);

		if (!class_exists('fileupload'))
		{
			include($phpbb_root_path . 'includes/functions_upload.' . $phpEx);
		}

		$upload = new fileupload();

		if (isset($files))
		{
			foreach($files as $name)
			{
				$temp = $upload->form_upload('templatefield_' . $name);

				if ($temp->get('realname'))
				{
					$names[] = $temp->get('realname');
				}
			}
		}

		foreach ($form_data as $row)
		{
			$name = "";
			$row['name'] = str_replace(' ', '_', $row['name']);
			$name = "templatefield_" . $row['name'];

			if (isset($row['type']))
			{
				switch ($row['type'])
				{
					case 'checkbox':

						$check_box_items = count(request_var('templatefield_' . $row['name'], array(0 => 0)));
						$check_box_count = 0;
						$appform_post .= '[b]' . $row['name'] . ':[/b][fbox]';

						$checkbox = utf8_normalize_nfc(request_var($name, array(0 => '') , true));

						foreach ($checkbox as $value)
						{
							$appform_post .= $value;

							if ($check_box_count < $check_box_items - 1)
							{
								$appform_post .= ', ';
							}
							$check_box_count++;
						}
						$appform_post .= '[/fbox]';

					break;

					case 'email':
					case 'password':
					case 'url':
					case 'text':
					case 'selectbox':
					case 'radiobuttons':
						$fieldcontents = utf8_normalize_nfc(request_var($name, ' ', true));
						// only process if element has valid data //
						if ($fieldcontents)
						{
							$appform_post .= '[b]' . $row['name'] . ':[/b][fbox]';
							$appform_post .= $fieldcontents .= '[/fbox]';
						}
					break;

					case 'file':
						if (isset($names[$file_count]))
						{
							$fieldcontents = utf8_normalize_nfc(request_var($name, ' ', true));
							// only process if element has valid data //
							if ($fieldcontents)
							{
								$appform_post .= '[b]' . $row['name'] . ':[/b][att]';
								$fieldcontents = '[attachment=' . $file_count . ']' . $names[$file_count] . '[/attachment]';
								$file_count++;
								$appform_post .= '<br />';
								$appform_post .= $fieldcontents .= '[/att]';
							}
						}
					break;

					case 'textbox':
						$fieldcontents = utf8_normalize_nfc(request_var($name, ' ', true));
						// only process if element has valid data //
						if ($fieldcontents)
						{
							$appform_post .= '[b]' . $row['name'] . ':[/b][fbox]';
							$appform_post .= $fieldcontents .= '[/fbox]';
						}
					break;

					default:
					break;
				}
			}
		}

		// prevent posting if form is empty //
		if ($fieldcontents == '')
		{
			return;
		}
		else return('[form]' . $appform_post . '[/form]');
	}
}

?>
