<?php
/**
 * Form template generator
 * @package form_maker
 * @link http://www.phpbbireland.com
 * @author phpbbirelandgmail.com
 * Code derived from: appform by: Sajaki
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
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

class acp_form_maker
{
	var $u_action;

	function main ($id, $mode)
	{
		global $db, $user, $auth, $template, $sid, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$first_forum_id = 0;

		$user->add_lang(array('mods/form_maker'));
		$this->tpl_name = 'acp_form_maker';
		$this->page_title = 'ACP_FORM_MAKER';

		$form_key = md5(uniqid(rand(), true));
		add_form_key($form_key);

		$mode = request_var('mode', 'manage');
		$fname = $link = '';
		$elements = 0;

		/*
			The main loop is executed on any post issued by the form...

			We do not use meta_refresh... we simply run through the code
			executing the desired action ($addnew, $move_down, $move_up,
			$delete and $update)...

			This make for faster execution so to prevent any finger issues,
			we have added an animated updating image (via jQuery) to let the
			user know a process is executing...

			On fast servers the page update is so quick, it looks like we are
			using Ajax...
		*/

		switch ($mode)
		{
			case 'manage':

				if ($first_forum_id == 0)
				{
					$first_forum_id = get_first_forum_id();
				}

				$form_id  = (int) request_var('form_id', $first_forum_id);
				$id = request_var('id', 0);

				$fname = get_forums($form_id);

				$link = '<br /><a href="' . append_sid("index.$phpEx", "i=form_maker&amp;mode=manage") . '">' . $user->lang['FORM_MAKER_ACP_RETURN'] . '</a>';
				$update = (isset($_POST['update'])) ? true : false;
				$addnew = (isset($_POST['add'])) ? true : false;
				$move_up = (isset($_GET['move_up'])) ? true : false;
				$move_down = (isset($_GET['move_down'])) ? true : false;
				$delete = (isset($_GET['delete'])) ? true : false;

				if ($update)
				{
					// ignore the rest if updating //
					$addnew = $move_down = $move_up = $delete = 0;
				}

				if ($move_down || $move_up)
				{
					$form_id = request_var('form_id', 0);
					$id = request_var('id', 0);

					$sql = 'SELECT ndx_order
						FROM ' . FORM_MAKER_TABLE . '
						WHERE id =  ' . $id . '
						AND form_id = ' . $form_id;

					$result = $db->sql_query($sql);

					$current_order = (int) $db->sql_fetchfield('ndx_order', 0, $result);

					$db->sql_freeresult($result);

					if ($move_down)
					{
						$new_order = $current_order + 1;
					}
					else
					{
						$new_order = ($current_order > 1) ? $current_order - 1 : 1;
					}

					// find current id with new order and move that one notch, if any
					$sql = 'UPDATE  ' . FORM_MAKER_TABLE . '
						SET ndx_order = ' . $current_order . '
						WHERE ndx_order = ' . $new_order . '
						AND form_id = ' . $form_id;
					$db->sql_query($sql);

					// now increase old order
					$sql = 'UPDATE  ' . FORM_MAKER_TABLE . '
						SET ndx_order = ' . $new_order . '
						WHERE id = ' . $id . '
						AND form_id = ' . $form_id;
					$db->sql_query($sql);

					$move_down = $move_up = false;

				}

				if($delete)
				{
					$sql = 'DELETE FROM ' . FORM_MAKER_TABLE . '
						WHERE id = ' . $id;

					$db->sql_query($sql);

					$form_id = request_var('form_id', 0);
				}

				//user pressed update contents
				if ($update)
				{
					$q_types      = utf8_normalize_nfc(request_var('q_type', array(0 => ''), true));
					$q_names      = utf8_normalize_nfc(request_var('q_name', array(0 => ''), true));
					$q_hint       = utf8_normalize_nfc(request_var('q_hint', array(0 => ''), true));
					$q_options    = utf8_normalize_nfc(request_var('q_options', array(0 => ''), true));
					$form_id      = request_var('form_id', 1);
					$q_ndx_order  = utf8_normalize_nfc(request_var('q_ndx_order', array(0 => ''), true));

					foreach ($q_hint as $key => $form_values)
					{
						$data = array('mandatory' => isset($_POST['q_mandatory'][$key]) ? '1' : '0');

						$sql = 'UPDATE ' . FORM_MAKER_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $data) . '
							WHERE id = ' . $key;

						$db->sql_query($sql);

						// updating contents //
						$data = array(
							'type'      => $q_types[$key] ,
							'name'      => $q_names[$key] ,
							'hint'      => $q_hint[$key] ,
							'options'   => $q_options[$key],
							'ndx_order' => $q_ndx_order[$key]
						);

						$sql = 'UPDATE ' . FORM_MAKER_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $data) . '
							WHERE id = ' . $key . '
							AND form_id = ' . $form_id;

						$db->sql_query($sql);
					}

					$template->assign_var('L_FORM_NO_FORM', sprintf($user->lang['FORM_NO_FORM'], $fname));
				}

				if ($addnew)
				{
					$form_id = request_var('form_id', 0);

					$sql = 'SELECT max(ndx_order) + 1 as maxorder
						FROM ' . FORM_MAKER_TABLE . '
						WHERE form_id = ' . $form_id;

					$result = $db->sql_query($sql);

					$max_order = (int) $db->sql_fetchfield('maxorder', 0, $result);
					$db->sql_freeresult($result);

					$sql_ary = array(
						'ndx_order' => ($max_order > 0) ? $max_order : 1, // we don't use index 0 //
						'form_id'   => $form_id,
						'name'      => utf8_normalize_nfc(request_var('name', ' ', true)) ,
						'hint'      => utf8_normalize_nfc(request_var('hint', ' ', true)) ,
						'options'   => utf8_normalize_nfc(request_var('options', ' ', true)) ,
						'type'      => utf8_normalize_nfc(request_var('add_type', ' ', true)) ,
						'mandatory' => (isset($_POST['mandatory']) ? '1' : '0'));

					// insert new contents
					$sql = 'INSERT INTO ' . FORM_MAKER_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
					$result = $db->sql_query($sql);

					if (!$result)
					{
						trigger_error($user->lang['FORM_MAKER_ACP_QUESTNOTADD'] . $link, E_USER_WARNING);
					}
				}


				// main sql //
				$sql = 'SELECT id, form_id, ndx_order, name, hint, type, mandatory, options, forum_name, forum_id
					FROM ' . FORM_MAKER_TABLE . ' m, ' . FORUMS_TABLE . " f
					WHERE m.form_id = $form_id
						AND m.form_id = f.forum_id
					ORDER BY ndx_order ASC";

				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$disabled = '';
					$checked = '';

					if ($row['mandatory'])
					{
						$checked = 'checked="checked"';
					}

					$template->assign_block_vars('form_template', array(
						'FID'		=> $row['form_id'],
						'NDX_ORDER'	=> $row['ndx_order'] ,
						'NAME'		=> $row['name'],
						'TYPE'		=> $row['type'],
						'HINT'		=> $row['hint'],
						'MANDATORY'	=> $row['mandatory'] ,
						'OPTIONS'	=> $row['options'] ,
						'CHECKED'	=> $checked ,
						'ID'		=> $row['id'] ,

						'U_DELETE' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=form_maker&amp;mode=manage&amp;delete=1&amp;id={$row['id']}&amp;form_id={$row['form_id']}") ,
						'U_MOVE_UP' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=form_maker&amp;mode=manage&amp;move_up=1&amp;id={$row['id']}&amp;form_id={$row['form_id']}") ,
						'U_MOVE_DOWN' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=form_maker&amp;mode=manage&amp;move_down=1&amp;id={$row['id']}&amp;form_id={$row['form_id']}")));

					$type = array(
						'text' ,
						'textbox' ,
						'selectbox' ,
						'radiobuttons' ,
						'checkbox',
						'password',
						'email',
						'url',
						'file'
					);

					foreach ($type as $t_name => $t_value)
					{
						$template->assign_block_vars('form_template.template_type', array(
							'TYPE'		=> $t_value ,
							'SELECTED'	=> ($t_value == $row['type']) ? ' selected="selected"' : '' ,
							'DISABLED'	=> $disabled
						));
						$elements++;
					}
				}

				$template->assign_vars(array(
					'FID'             => $form_id,
					'ELEMENTS'        => $elements,
					'L_FORM_NO_FORM'  => sprintf($user->lang['FORM_NO_FORM'], $fname),
				));

				$db->sql_freeresult($result);

				$this->page_title = $user->lang['ACP_FORM_MAKER'];
				$this->tpl_name = 'acp_form_maker';
				break;

				default:

				break;
		}

		// Next section is commented out as only used when checking code during development //


		$template->assign_vars(array(
			'REPORT'  => $user->lang['MODE'] . ' = [' . $mode . '] | ' . $user->lang['ELEMENTS'] . ' = [' . $elements . '] | ' . $user->lang['FORUM_ID'] . ' = [' . $form_id . '] | ' . $user->lang['FORM_NAME'] . ' = [' . $fname . ']',
			'LINK'	=> $link,
		));

		build_preview($form_id);
	}
}

function get_forums($form_id)
{
	global $db, $template, $first_forum_id;
	$store = '';

	$sql = 'SELECT forum_id, forum_name
		FROM ' . FORUMS_TABLE . '
		WHERE forum_type = ' . FORUM_POST . '
		ORDER BY forum_id ASC';

	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$has_form = false;
		if (has_form($row['forum_id']))
		{
			$has_form = true;
			$chk  = " ✔";
		}
		else
		{
			$chk  = "";
		}

		$template->assign_block_vars('forms', array(
			'FORUM_ID'   => $row['forum_id'],
			'FORUM_NAME' => $row['forum_name'],
			'SELECT'     => ($row['forum_id'] == $form_id) ? ' selected="selected"' : '',
			'HAS_FORM'   => $has_form,
			'CHK'        => $chk,
		));

		if ($form_id == $row['forum_id'])
		{
			$store = $row['forum_name'];
		}
	}
	$db->sql_freeresult($result);
	return($store);
}

function get_first_forum_id()
{
	global $db, $template, $first_forum_id;

	$sql = 'SELECT forum_id
		FROM '. FORUMS_TABLE . '
		WHERE forum_type = ' . FORUM_POST . '
		ORDER BY forum_id ASC';

	$result = $db->sql_query($sql, 1);
	$row = $db->sql_fetchfield('forum_id');
	$db->sql_freeresult($result);
	return($row);
}

/***
* Sections of this code are taken from the Appform Mod, copyright Sajaki 2012
**/

function build_preview($form_id)
{
	global $db, $template;

	$sql = 'SELECT id, form_id, ndx_order, name, hint, type, mandatory, options, forum_name, forum_id
		FROM ' . FORM_MAKER_TABLE . ' m, ' . FORUMS_TABLE . " f
		WHERE m.form_id = $form_id
			AND m.form_id = f.forum_id
		ORDER BY ndx_order ASC";

	$result = $db->sql_query($sql);

	$file_count = -1;

	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['type'] == 'file')
		{
			$file_count++;
		}

		switch (strtolower($row['type']))
		{
			case 'email':
			case 'password':
			case 'url':
			case 'text':
			case 'file':
				$type = '<input style="border-radius: 5px; width:300px" class="text" type="' . $row['type']. '" name="templatefield_' . $row['name'] . $file_count . '" placeholder="' . $row['hint'] . '" size="40" maxlength="255" tabindex="' . $row['ndx_order'] . '" />';
			break;

			case 'textbox':
				$type = '<textarea style="border-radius: 5px;" class="text" name="templatefield_' . $row['name'] . '" rows="3" cols="76" tabindex="' . $row['ndx_order'] . '" onselect="storeCaret(this);" onclick="storeCaret(this);" placeholder="' . $row['hint'] . '" onkeyup="storeCaret(this);"></textarea>';
			break;
			case 'selectbox':
				$type = '<select style="border-radius: 5px;" class="inputbox" name="templatefield_' . $row['name'] . '" tabindex="' . $row['ndx_order'] . '">';
				$type .= '<option value="">----------------</option>';
				$select_option = explode(',', $row['options']);
				foreach($select_option as $value)
				{
					$type .='<option value="'. $value .'">'. $value .'</option>';
				}
				$type .= '</select>';
			break;
			case 'radiobuttons':
				$radio_option = explode(',', $row['options']);

				$type = '';
				foreach($radio_option as $value)
				{
					$type .='<input type="radio" name="templatefield_'. $row['name'] .'" value="'. $value . '" />&nbsp;'. $value .'&nbsp;&nbsp;';
				}
			break;
			case 'checkbox':
				$check_option = explode(',', $row['options']);

				$type = '';
				foreach($check_option as $value)
				{
					$type .='<input type="checkbox" name="templatefield_'. $row['name'].'[]" value="'. $value .'" />&nbsp;'. $value .'&nbsp;&nbsp;';
				}
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
			'HINT'      => $row['hint'],
			'OPTIONS'   => $row['options'],
			'TYPE'      => (isset($type)) ? $type : '',
			'MANDATORY' => $mandatory)
		);

	}
	$db->sql_freeresult($result);
}

function has_form($form_id)
{
	global $db;

	$sql = 'SELECT forum_name, forum_id
		FROM ' . FORM_MAKER_TABLE . ' m, ' . FORUMS_TABLE . " f
		WHERE m.form_id = $form_id
			AND m.form_id = f.forum_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	if ($row)
	{
		return(true);
	}
	else
	{
		return(false);
	}
}
?>
