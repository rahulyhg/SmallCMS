<?php
require_once("includes/connection.php");
require_once("includes/functions.php");

//Form Validation

$errors = array();

if (!isset($_POST['menu_name']) || empty($_POST['menu_name']))
{
	$errors[] = 'menu_name';
}

if (!isset($_POST['position']) || !is_numeric($_POST['position']))
{
	$errors[] = 'position';
}

if (!isset($_POST['visible']))
{
	$errors[] = 'visible';
}

if (!empty($errors))
{
	header('Location:new_subject.php');
	exit();
}

////////////////////////////////

$menu_name = mysql_prep($_POST['menu_name']);
$position = mysql_prep($_POST['position']);
$visible = mysql_prep($_POST['visible']);

$query = "INSERT INTO subjects (
			menu_name, position, visible) 
			values (
			'${menu_name}', ${position}, ${visible})";
if (mysql_query($query, $connection))
{
	header("Location: content.php");
	exit;
}
else
{
	echo "<p>Subject creation failed.</p>";
	echo $query;
	echo "<p>" . mysql_error() . "</p>";
}

mysql_close($connection);
