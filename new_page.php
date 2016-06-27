<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php
	if (isset($_GET['subj']))
	{
		$sel_subject = get_subject_by_id($_GET['subj']);
		$sel_page = NULL;
	}

?>
<?php include ("includes/header.php"); ?>

<div class="content">
	<div class="main">
		<h2>
<?php
		if (!is_null($sel_subject))
		{
			echo $sel_subject->menu_name;
		} 
		else 
		{
			echo "Select a subject or page to edit";
		}
?>
		</h2>
		<div class="page_content">
		<?php 
			if (!is_null($sel_page))
			{
				echo $sel_page->content;
			}
		?>
		</div>

	</div> <!-- end main-->
	<div class="sidebar">
	
	<?php echo navigation ($sel_subject, $sel_page);?>

	<p><a href="new_subject.php">+ Add a new Subject</a></p>
	</div><!-- end sidebar-->
</div><!-- end content-->

<?php include ("includes/footer.php");?>