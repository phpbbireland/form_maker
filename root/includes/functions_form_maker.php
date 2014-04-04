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

if (!function_exists('build_form'))
{
	function build_form($forum_id)
	{
		global $db, $template, $mode;

		$style = 'style="border-radius: 5px;"';
		$styleTA = 'style="border-radius: 5px; max-width: 400px;"';
		$entry = "";

		$sql = "SELECT id, form_id, ndx_order, name, hint, type, mandatory, options, forum_name, forum_id
			FROM " . FORM_MAKER_TABLE . " AS m, " . FORUMS_TABLE . " AS f
			WHERE m.form_id = '" . $forum_id . "'
			AND m.form_id = f.forum_id
			ORDER BY ndx_order";

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['mandatory'])
			{
				$mand = " required ";
			}
			else
			{
				$mand = "";
			}

			$row['name'] = str_replace(' ', '_', $row['name']);

			// make thigs a little easier to read //
			$na = "name='templatefield_" . $row['name'] . "'";
			$id = "id='templatefield_" . $row['name'] . "'";

			switch (strtolower($row['type']))
			{
				case 'email':
				case 'password':
				case 'url':
				case 'text':
				case 'file':
					$entry = '<input type="' . $row['type'] . '"' . $na . '" value="' . $row['hint'] . '"' . $id . '" placeholder="' . $row['hint'] . '" ' . $mand . ' size="40" maxlength="255" tabindex="' . $row['ndx_order'] . '" ' . $style . ' />';
				break;
				case 'textbox':
					$entry = '<textarea type="textbox"' . $na . '"' . $id . '" rows="3" cols="76" tabindex="' . $row['ndx_order'] . '" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" placeholder="' . $row['hint'] . '"' . $mand . ' ' . $styleTA . '></textarea>';
				break;
				case 'selectbox':
					$entry = '<select type="select"' . $na . '"' . $id . '" tabindex="' . $row['ndx_order'] . '"' . $mand . ' ' . $style . '>';
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
					   $entry .='<input type="radio" ' . '" tabindex="' . $row['ndx_order'] . '"' . $mand . $na . '"' . $id . '" value="'. $value .'"/>&nbsp;'. $value .'&nbsp;&nbsp;';
					}
				break;
				case 'checkbox':
					$check_option = explode(',', $row['options']);

					$entry = '';
					foreach($check_option as $value)
					{
					   $entry .='<input type="checkbox" ' . '" tabindex="' . $row['ndx_order'] . '"' . $mand . ' name="templatefield_' . $row['name'] .'[]"' . $id . '" value="'. $value .'" />&nbsp;'. $value .'&nbsp;&nbsp;';
					}
				break;
				default:
				break;
			}

			$mandatory = '';

			if ($row['mandatory'] == '1')
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

if (!function_exists('grab_form_data'))
{
	function grab_form_data($forum_id)
	{
		global $auth, $config, $db, $user, $phpbb_root_path, $phpEx;

		$ret = $appform_post = $cr_chr = '';
		$name_length_max = 1;
		$file_count = 0;
		$form_data = array();

		$config['title_colour'] = (isset($config['title_colour'])) ? $config['title_colour'] : '#FF0000';

		$sql = "SELECT *
			FROM " . FORM_MAKER_TABLE . "
			WHERE form_id = " . $forum_id . "
			ORDER BY ndx_order";
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
		}
		$db->sql_freeresult($result);

		// get longest name for padding calculation //
		foreach ($form_data as $data)
		{
			$len = strlen($data['name']);

			if ($name_length_max < $len)
			{
				$name_length_max = $len;
			}
		}

		// grab the name of the attached file //
		foreach($_FILES as $key => $file)
		{
			if(isset($file['name']))
			{
				$names[] = $key;
			}
		}

		foreach ($form_data as $row)
		{
			$spaces = " ";
			$na = "";

			for ($i = 0; $i < (($name_length_max + 1) - strlen($row['name'])); $i++)
			{
				$spaces .=  " ";
			}

			// make textbox look nicer //
			if ($row['type'] == 'textbox')
			{
				$ret = $spaces;
			}

			$row['name'] = str_replace(' ', '_', $row['name']);
			$na = "templatefield_" . $row['name'] . "";

			if (isset($_POST[$na]) || isset($_FILES['templatefield_' . $row['name']]))
			{
				switch ($row['type'])
				{
					case 'checkbox':
						$check_box_items = count(request_var('templatefield_' . $row['name'], array(0 => 0)));
						$check_box_count = 0;

						$appform_post .= '[b]' . $row['name'] . ':' . $spaces .' [/b]';

						$checkbox = utf8_normalize_nfc(request_var($na, array(0 => '') , true));

						foreach ($checkbox as $value)
						{
							$appform_post .= $value;

							if ($check_box_count < $check_box_items - 1)
							{
								$appform_post .= ', ';
							}
							$check_box_count++;
						}
						$appform_post .= '<br />';

					break;

					case 'email':
					case 'password':
					case 'url':
					case 'text':
					case 'selectbox':
					case 'radiobuttons':
						$fieldcontents = utf8_normalize_nfc(request_var($na, ' ', true));
						$appform_post .= '[b]' . $row['name'] . ':' . $spaces . '[/b]';
						$appform_post .= $fieldcontents .= '<br />';
					break;

					case 'file':
						$fieldcontents = '[attachment=' . $file_count . ']' . $_FILES[$names[$file_count]]['name'] . '[/attachment]';
						$file_count++;
						$appform_post .= '[/tab]<br />';
						$appform_post .= $fieldcontents .= '[tab]';
					break;

					case 'textbox':
						$fieldcontents = utf8_normalize_nfc(request_var($na, ' ', true));
						$appform_post .= '[b]' . $row['name'] . ':' . $spaces . '[/b]';

						for ($i = 0; $i < strlen($fieldcontents); $i++)
						{
							if ($fieldcontents[$i] == "\0" || $fieldcontents[$i] == "\n\r" || $fieldcontents[$i] == "\n")
							{
								$cr_chr = $fieldcontents[$i];
							}
						}

						$k = strlen($spaces);
						$x = strlen($row['name']);
						$spaces = "  ";
						for ($j = 0; $j < $x + $k; $j++)
						{
							$spaces .= " ";
						}

						if ($cr_chr != '')
						{
							$fieldcontents = str_replace($cr_chr, $cr_chr . $spaces, $fieldcontents);
						}

						$appform_post .= $fieldcontents .= '<br />';

					break;

					default:
					break;
				}
			}
			else
			{
				//echo 'Error name = [' .  $na . ']<br/>';
			}
		}
		return('[tab]' . $appform_post . '[/tab]');
	}
}


if (!function_exists('build_edit_form'))
{
	function build_edit_form($forum_id, $message)
	{
		return; // next time //
	}

}

?>