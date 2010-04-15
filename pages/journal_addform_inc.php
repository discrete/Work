<?php 
function worktype_get_all_rows( $p_project_id, $p_inherit = null, $p_sort_by_project = false ) {
	$c_project_id = db_prepare_int( $p_project_id );

	$t_worktype_table = plugin_table( 'type' );
	$t_project_table = db_get_table( 'mantis_project_table' );

	if ( $c_project_id == ALL_PROJECTS ) {
		$t_inherit = false;
	} else {
		if ( $p_inherit === null ) {
			$t_inherit = config_get( 'subprojects_inherit_categories' );
		} else {
			$t_inherit = $p_inherit;
		}
	}

	if ( $t_inherit ) {
		$t_project_ids = project_hierarchy_inheritance( $p_project_id );
		$t_project_where = ' project_id IN ( ' . implode( ', ', $t_project_ids ) . ' ) ';
	} else {
		$t_project_where = ' project_id=' . $p_project_id . ' ';
	}

	$query = "SELECT t.*, p.name AS project_name FROM $t_worktype_table AS t
				LEFT JOIN $t_project_table AS p
					ON t.project_id=p.id
				WHERE $t_project_where
				ORDER BY t.name ";
	$result = db_query_bound( $query );
	$count = db_num_rows( $result );
	$rows = array();
	for( $i = 0;$i < $count;$i++ ) {
		$row = db_fetch_array( $result );

		$rows[] = $row;
	}

	return $rows;
}

function worktype_get_row( $p_worktype_id ) {
	$c_worktype_id = db_prepare_int( $p_worktype_id );

	$t_worktype_table = plugin_table( 'type' );
	$t_project_table = db_get_table( 'mantis_project_table' );

	$query = "SELECT * FROM $t_worktype_table
				WHERE id=" . db_param();
	$result = db_query_bound( $query, array( $c_worktype_id ) );
	$count = db_num_rows( $result );
	if( 0 == $count ) {
		trigger_error( ERROR_CATEGORY_NOT_FOUND, ERROR );
	}

	$row = db_fetch_array( $result );
	return $row;
}

function worktype_full_name( $p_worktype_id, $p_show_project = true, $p_current_project = null ) {
	if( 0 == $p_worktype_id ) {
		# No Category
		return 'No Worktype';
	} else {
		$t_row = worktype_get_row( $p_worktype_id );
		$t_project_id = $t_row['project_id'];

		$t_current_project = is_null( $p_current_project ) ? helper_get_current_project() : $p_current_project;

		if( $p_show_project && $t_project_id != $t_current_project ) {
			return '[' . project_get_name( $t_project_id ) . '] ' . $t_row['name'];
		}

		return $t_row['name'];
	}
}

function print_worktype_option_list( $p_worktype_id = 0, $p_project_id = null ) {
	$t_worktype_table = plugin_table( 'type' );
	$t_project_table = db_get_table( 'mantis_project_table' );

	if( null === $p_project_id ) {
		$t_project_id = helper_get_current_project();
	} else {
		$t_project_id = $p_project_id;
	}

	if( 0 == $p_worktype_id ) {
		echo "<option value=\"0\"", check_selected( $p_worktype_id, 0 ), '>';
		echo string_attribute( lang_get( 'select_option' ) ), '</option>';
	}

	$worktype_arr = worktype_get_all_rows( $t_project_id, /* inherit */ null, /* sortByProject */ true );

	foreach( $worktype_arr as $t_worktype_row ) {
		$t_worktype_id = $t_worktype_row['id'];
		echo "<option value=\"$t_worktype_id\"";
		check_selected( $p_worktype_id, $t_worktype_id );
		echo '>' . string_attribute( worktype_full_name( $t_worktype_id, $t_worktype_row['project_id'] != $t_project_id ) ) . '</option>';
	}
}
?>

<a name="addwork"></a> <br />

<?php
	collapse_open( 'work_add' );
?>
<form name="journal_add" method="post" action="<?php echo plugin_page('journal_add.php')?>">
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
		Jobs: 
		<select name="worktype_id">
				<?php
					print_worktype_option_list( '0' );
				?>
		</select>
	</td>
	<td width="25%">
		man-days<input name='man_day' type='text' />
	</td>
</tr>

<!-- ?php event_signal( 'EVENT_WORK_ADD_FORM', array( $p_bug_id ) ); ?-->
<tr>
	<td class="center" colspan="2">
		<input type="submit" class="button" value="Add Work"  onclick="this.disabled=1;document.journal_add.submit();" />
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
