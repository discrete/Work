<a name="addbugnote"></a> <br />

<?php
	collapse_open( 'work_add' );
?>
<form name="workadd" method="post" action="<?php echo plugin_page('Work_add.php')?>">
<input type="hidden" name="bug_id" value="<?php echo $p_bug_id ?>" />
<table class="width100" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
<?php
	collapse_icon( 'work_add' );
?>
	</td>
</tr>
<tr class="row-2">
	<td class="category" width="25%">
		Work
	</td>
	<td width="50%">
		Jobs: <select>
			<option>Research</option>
			<option>Merge</option>
			<option>Implementation</option>
			<option>Debugging</option>		
		</select>
	</td>
	<td width="25%">
		man-days<input type='text' />
	</td>
</tr>

<!-- ?php event_signal( 'EVENT_WORK_ADD_FORM', array( $p_bug_id ) ); ?-->
<tr>
	<td class="center" colspan="2">
		<input type="submit" class="button" value="Add Work"  onclick="this.disabled=1;document.bugnoteadd.submit();" />
	</td>
</tr>
</table>
</form>
<?php
	collapse_closed( 'work_add' );
?>
<table class="width100" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
	<?php	collapse_icon( 'work_add' ); ?>
	</td>
</tr>
</table>
<?php
	collapse_end( 'work_add' );
?>
