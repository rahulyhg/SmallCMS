<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php
	find_selected_page();
?>
<?php include ("includes/header.php"); ?>

<div class="content">
	<div class="main">
	<h2>Add Subject</h2>
	<form action="create_subject.php" method="post">
		<fieldset>
			<label for="menu_name">Subject Name:</label>
			<input type="text" name="menu_name" value="" id="menu_name">
		</fieldset>
		<fieldset>
			<label for="position">Position:</label>
			<?php 
				$rsSubject = get_all_subjects();
				$subject_count = mysql_num_rows($rsSubject);
			?>
			<select name="position">
			<?php
				for ($count=1;$count <=$subject_count+1;$count++)
				{
					echo '<option value="'. $count. '">'.$count.'</option>';
				}
			?>
			</select>
		</fieldset>
		
		<fieldset>
			<label for="visible">Visible:</label>
			<input type="radio" name="visible" value="0">No &nbsp; 
			<input type="radio" name="visible" value="1" checked>Yes
		</fieldset>
		<fieldset>
			<input type="submit" value="Add Subject" name="submit">
		</fieldset>

	</form>
	
	<p><a href="content.php">Cancel</a></p>
	
	</div> <!-- end main-->
	<div class="sidebar">
	<?php echo navigation($sel_subject, $sel_page);?>
	</div><!-- end sidebar-->
</div><!-- end content-->

<?php include ("includes/footer.php");?>