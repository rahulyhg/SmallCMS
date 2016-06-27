<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php

$res = get_pages_for_subject(1);

while ($row = mysql_fetch_object($res)) {
	print_r($row);

}
?>