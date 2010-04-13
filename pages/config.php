<?php
# MantisBT - a php based bugtracking system
# Copyright (C) 2002 - 2010  MantisBT Team - mantisbt-dev@lists.sourceforge.net
# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

function worktype_get_all_rows($p_project_id)
{
	$c_project_id = db_prepare_int( $p_project_id );

	$t_type_table = plugin_table( 'type' );
	$t_project_table = db_get_table( 'mantis_project_table' );

	if ( $c_project_id == ALL_PROJECTS ) {
		$t_inherit = false;
	}
	else {
		$t_inherit = false;
	}

	if ( $t_inherit ) {
		$t_project_ids = project_hierarchy_inheritance( $p_project_id );
		$t_project_where = ' project_id IN ( ' . implode( ', ', $t_project_ids ) . ' ) ';
	}
	else {
		$t_project_where = ' project_id=' . $p_project_id . ' ';
	}

	$query = "SELECT t.*, p.name AS project_name FROM $t_type_table AS t
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

	if( $p_sort_by_project ) {
		/*
		category_sort_rows_by_project( $p_project_id );
		usort( $rows, 'category_sort_rows_by_project' );
		category_sort_rows_by_project( null );
		*/
	}

	return $rows;
}
?>

<?php
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

html_page_top( 'Work' );

print_manage_menu( );

?>

<!-- PROJECT WorkType --> 
<a name="WorkType"></a>
<div align="center"> 
<table class="width75" cellspacing="1"> 
 
<!-- Title --> 
<tr> 
	<td class="form-title" colspan="3"> 
		Work Type	</td> 
</tr> 
<?php
	$f_project_id = 1; /* gpc_get_int( 'project_id' ); */
	$t_worktypes = worktype_get_all_rows( $f_project_id );

	if ( count( $t_worktypes ) > 0 ) {
?>
		<tr class="row-category">
			<td>
				Type
			</td>
			<td>
				Assign to
			</td>
			<td class="center">
				Action
			</td>
		</tr>
<?php
	}
	
		foreach ( $t_worktypes as $t_worktype ) {
		$t_id = $t_worktype['id'];

		if ( $t_worktype['project_id'] != $f_project_id ) {
			$t_inherited = true;
		} else {
			$t_inherited = false;
		}

		$t_name = $t_worktype['name'];
		if ( NO_USER != $t_worktype['user_id'] && user_exists( $t_worktype['user_id'] )) {
			$t_user_name = user_get_name( $t_worktype['user_id'] );
		} else {
			$t_user_name = '';
		}
?>
<!-- Repeated Info Row -->
		<tr <?php echo helper_alternate_class() ?>>
			<td>
				<?php echo string_display( category_full_name( $t_worktype['id'] , /* showProject */ $t_inherited, $f_project_id ) )  ?>
			</td>
			<td>
				<?php echo string_display_line( $t_user_name ) ?>
			</td>
			<td class="center">
				<?php if ( !$t_inherited ) {
					$t_id = urlencode( $t_id );
					$t_project_id = urlencode( $f_project_id );

					print_button( plugin_page('config_worktype_edit.php') . '?id=' . $t_id . '&project_id=' . $t_project_id, lang_get( 'edit_link' ) );
					echo '&nbsp;';
					print_button( plugin_page('config_worktype_delete.php') . '?id=' . $t_id . '&project_id=' . $t_project_id, lang_get( 'delete_link' ) );
				} ?>
			</td>
		</tr>
<?php
	} # end for loop
?>
<!-- Add WorkType Form --> 
<tr> 
	<td class="left" colspan="3"> 
		<form method="post" action="<?php echo plugin_page('config_worktype_add.php')?>"> 
			<input type="hidden" name="project_id" value="1" /> 
			<input type="text" name="name" size="32" maxlength="128" /> 
			<input type="submit" class="button" value="Add Work Type" /> 
		</form> 
	</td> 
</tr> 
 
<!-- Copy Categories Form --> 
<tr> 
	<td class="left" colspan="3"> 
		<form method="post" action="manage_proj_cat_copy.php"> 
			<input type="hidden" name="manage_proj_cat_copy_token" value="20100413d73955e122ac5d9355b614de672c776a22f195b0"/>			<input type="hidden" name="project_id" value="1" /> 
			<select name="other_project_id"> 
							</select> 
			<input type="submit" name="copy_from" class="button" value="Copy Categories From" /> 
			<input type="submit" name="copy_to" class="button" value="Copy Categories To" /> 
		</form> 
	</td> 
</tr> 
</table> 
</div>

<?php
html_page_bottom();
