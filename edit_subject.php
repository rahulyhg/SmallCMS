<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php	
	if (isset($_POST['delete']))
	{
		if (isset($_POST['subject_id']))
		{
			$sql = "delete from subjects where id={$_POST['subject_id']} limit 1";
			$result = mysql_query($sql, $connection);
			if (mysql_affected_rows() == 1)
			{
				header('Location:content.php');
				exit();
			}
			else
			{
				$message = 'Delete Failed &mdash; ';
				$message .= mysql_error();
			}
		}
	}
	else if (isset($_POST['submit']))
	{
		//Validate form
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
		////////////////////////////////
		
		if (empty($errors))
		{
			//update database
			$id = $_POST['subject_id'];
			$menu_name = mysql_prep($_POST['menu_name']);
			$position = mysql_prep($_POST['position']);
			$visible = mysql_prep($_POST['visible']);
			
			$query = "UPDATE subjects SET 
				menu_name = '{$menu_name}',
				position = {$position},
				visible = {$visible}
				WHERE id = {$id};";
				
			$result = mysql_query ($query, $connection);
			if (mysql_affected_rows() == 1)
			{
				//success
				$sel_subject = get_subject_by_id($id);
				$message = "Update Successfull";
			}
			else
			{
				//fail
				$message = "Update Failed &mdash; " . mysql_error();
			}		
		}
		else
		{
			//process errors
			$message = "The following field(s) were(are) invalid:";			
			foreach ($errors as $error)
			{
				$message .= ' ';
				$message .= $error;
			}
		}
	}//end if submit
	else 
	{
		find_selected_page();
		if (!$sel_subject)
		{
			header('Location:content.php');
			exit(0);
		}
	}
	
?>
<?php include ("includes/header.php"); ?>

<div class="content">
	<div class="main">
	<h2>Edit Subject <?php echo $sel_subject->menu_name;?></h2>
	<form action="edit_subject.php" method="post">
		<fieldset>
			<label for="menu_name">Subject Name:</label>
			<input type="text" name="menu_name" value="<?php echo $sel_subject->menu_name;?>" id="menu_name">
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
					$selected = '';
					if ($sel_subject->position == $count)
					{
						$selected=' selected ';
					}
					echo '<option value="'. $count. '"' . $selected . '>'.$count.'</option>';
				}
			?>
			</select>
		</fieldset>
		
		<?php
		?>
		<fieldset>
			<label for="visible">Visible:</label>
			<input type="radio" name="visible" value="0" <?php echo ($sel_subject->visible)?'': ' checked ';?>>No &nbsp; 
			<input type="radio" name="visible" value="1" <?php echo ($sel_subject->visible)?' checked ': '';?>>Yes
		</fieldset>
		<fieldset>
			<input type="submit" value="Update Subject" name="submit">
			<input type="submit" value="Delete Subject" name="delete" onclick="return confirm_delete('<?php echo $sel_subject->menu_name;?>')">
		</fieldset>
		<input type="hidden" name="subject_id" value="<?php echo $sel_subject->id;?>">
	</form>
	
	<p><a href="new_page.php?subj=<?php echo urlencode($sel_subject->id);?>">+ Add Page for this subject</a></p>
	
	<p><a href="content.php">Cancel</a></p>
	<p class="message">
	<?php 
		if (!empty($message))
		{
			echo $message;
		}
	?>
	</p>
	
	</div> <!-- end main-->
	<div class="sidebar">
	<?php echo navigation($sel_subject, $sel_page);?>
	</div><!-- end sidebar-->
</div><!-- end content-->

<?php include ("includes/footer.php");?>
<script type="text/javascript">
function confirm_delete(subject)
{
	return confirm('Are you sure you want to delete ' + subject + '?');
}
</script>

