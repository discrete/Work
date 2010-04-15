<?php 
function work_journal_get_all_rows($p_bug_id)
{

	$t_worktype_table = plugin_table( 'type' );
	$t_journal_table = plugin_table( 'journal' );
	$t_user_table = db_get_table('mantis_user_table');
	
	$query = "SELECT t.name name, j.man_day man_day, u.username username
				FROM $t_journal_table AS j, $t_worktype_table AS t, $t_user_table AS u
				WHERE j.work_id = t.id AND j.author_id = u.id AND j.bug_id = $p_bug_id";
	
	$result = db_query_bound( $query );
	$count = db_num_rows( $result );
	$rows = array();
	for( $i = 0;$i < $count;$i++ ) {
		$row = db_fetch_array( $result );

		$rows[] = $row;
	}

	return $rows;
}

function work_journal_get_total_manday($p_bug_id)
{
	$t_journal_table = plugin_table('journal');
	$query = "select sum(man_day) total_manday from $t_journal_table where bug_id = $p_bug_id";
	
	$result = db_query($query);
	while( $row = db_fetch_array( $result ) ) {
		$t_results[] = $row;
	}
	return$t_results;
}
?>
<?php
	collapse_open( 'Work' );
?>
<br/>
<a name="works"/></a>
<table class="width100" cellspacing="1">

<tr>
	<td class="form-title">
<?php 
	collapse_icon( 'Work' );
	echo 'Related Works';
?>

	</td>
</tr>
<?php
	$t_journals = work_journal_get_all_rows( $p_bug_id );

	if ( count( $t_journals ) > 0 ) {
?>
		<tr class="row-category">
			<td>
				<?php echo lang_get( 'category' ) ?>
			</td>
			<td>
				<?php echo lang_get( 'assign_to' ) ?>
			</td>
			<td class="center">
				<?php echo lang_get( 'actions' ) ?>
			</td>
		</tr>
<?php
	}
	foreach ( $t_journals as $t_journal ) {
?>
<!-- Repeated Info Row -->
		<tr>
			<td class="category" width="25%">
				<?php echo string_display ($t_journal['name'])?>
			</td>
			<td>
				Median research man-day: 0.5
			</td>
			<td>
				actual research man-day on this issue: <?php echo string_display ($t_journal['man_day'])?>	
			</td>
			<td>
				done by <?php echo string_display($t_journal['username'])?>
			</td>
		</tr>
<!-- 		
		<tr <?php echo helper_alternate_class() ?>>
			<td>
				<?php echo string_display( category_full_name( $t_journal['id'], false ) )  ?>
			</td>
			<td>
				<?php echo string_display_line( $t_user_name ) ?>
			</td>
			<td class="center">
				<?php
					$t_id = urlencode( $t_id );
					$t_project_id = urlencode( ALL_PROJECTS );

					print_button( "manage_proj_cat_edit_page.php?id=$t_id&project_id=$t_project_id", lang_get( 'edit_link' ) );
					echo '&nbsp;';
					print_button( "manage_proj_cat_delete.php?id=$t_id&project_id=$t_project_id", lang_get( 'delete_link' ) );
				?>
			</td>
		</tr>
-->
<?php
	} # end for loop
	$t_total_mandays = work_journal_get_total_manday($p_bug_id);
?>
<tr>
	<td class="category" width="25%">
		Total Work
	</td>
	
	<td>
		Median man-day: 1
	</td>
	<td>
		actual man-day on this issue: <?php echo string_display($t_total_mandays[0]['total_manday'])?>	
	</td>
	<td>
		involve 1 resource	
	</td>
</tr>

		<!-- ?php Source_View_Changesets( $t_changesets ); ?-->
</table>
<?php
			collapse_closed( 'Work' );
?>
<br/>
<table class="width100" cellspacing="1">

<tr>
	<td class="form-title">
<?php
			collapse_icon( 'Work' );
			echo 'Related Works';
?>
	</td>
</tr>

</table>
<?php
	collapse_end('Work');
?>