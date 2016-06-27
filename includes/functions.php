<?php
require_once('connection.php');

function mysql_prep($value)
{
	$magic_quotes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists("mysql_real_escape_string");
	if ($new_enough_php)
	{
		if ($magic_quotes_active) 
		{
			$value = stripslashes($value);
		}
		else
		{
			if (!$magic_quotes_active)
			{
				$value = addslashes($value);
			}
		}
	}
	return $value;
}

function confirm_query($result)
{
	if (!$result) {
		die("Database query failed: " . mysql_error());
	}
}

function get_all_subjects() 
{
	global $connection;
	$query = 'SELECT * ';
	$query .= 'FROM subjects where visible=1 order by position';
	$result = mysql_query($query, $connection);
	confirm_query($result);
	return $result;
}

function get_pages_for_subject($subject_id)
{
	global $connection;
	$query ='SELECT * FROM pages where visible=1 and subject_id=';
	$query .= $subject_id;
	$query .= ' order by position';
	$result = mysql_query($query, $connection);
	confirm_query($result);	
	return $result;
}

function get_subject_by_id($id)
{
	global $connection;
	$query = 'SELECT * ';
	$query .= 'FROM subjects ';
	$query .= 'WHERE id=';
	$query .= $id;
	$query .= ' LIMIT 1 ';
	$result = mysql_query($query, $connection);
	confirm_query($result);
	if ($row = mysql_fetch_object($result))
	{
		return $row;
	}
	return NULL;
}

function get_page_by_id($id)
{
	global $connection;
	$query = 'SELECT * ';
	$query .= 'FROM pages ';
	$query .= 'WHERE  id=';
	$query .= $id;
	$query .= ' LIMIT 1 ';
	$result = mysql_query($query, $connection);
	confirm_query($result);
	if ($row = mysql_fetch_object($result))
	{
		return $row;
	}
	return NULL;
}

function find_selected_page()
{
	global $sel_subject;
	global $sel_page;
	if (isset($_GET['subj']) && is_numeric($_GET['subj']))
	{
		$sel_subject = get_subject_by_id($_GET['subj']);
		$sel_page = NULL;
	}
	else if (isset($_GET['page'])  && is_numeric($_GET['page']))
	{
		$sel_page = get_page_by_id($_GET['page']);
		$sel_subject = NULL;
	}
	else
	{
		$sel_subject = NULL;
		$sel_page = NULL;
	}
}	

function navigation ($sel_subject, $sel_page)
{
	$selected_subject = ($sel_subject==NULL) ? 0 : $sel_subject->id;
	$selected_page = ($sel_page==NULL) ? 0 : $sel_page->id;
	$output = '<ul class="subjects">';
	$rsSubjects = get_all_subjects();
	while ($rowSubject = mysql_fetch_object($rsSubjects)) {
		$output .= '<li';
			if ($rowSubject->id == $selected_subject)
			{
				$output .= ' class="selected"';
			}
			$output .= '><a href="edit_subject.php?subj=' . urlencode($rowSubject->id). '">' . $rowSubject->menu_name . '</a></li>';
	
			$rsPages= get_pages_for_subject($rowSubject->id);
			$output .= '<ul class="pages">';
			
			while ($rowPage = mysql_fetch_object($rsPages)) {
			$output .= '<li';
			if ($rowPage->id == $selected_page)
			{
				$output .= ' class="selected"';
			}
			
			$output .= '><a href="content.php?page=' . urlencode($rowPage->id). '">' . $rowPage->menu_name . '</a></li>';
			}
			$output .= '</ul>';
		}
	$output .= '</ul>';
	return $output;
}
