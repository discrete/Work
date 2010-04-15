<?php
require_once( 'core.php' );

function worktype_is_unique( $p_project_id, $p_name ) {
	$c_project_id = db_prepare_int( $p_project_id );

	$t_worktype_table = plugin_table( 'type' );

	$query = "SELECT COUNT(*) FROM $t_worktype_table
					WHERE project_id=" . db_param() . " AND " . db_helper_like( 'name' );
	$count = db_result( db_query_bound( $query, array( $c_project_id, $p_name ) ) );

	if( 0 < $count ) {
		return false;
	} else {
		return true;
	}
}

function worktype_ensure_unique( $p_project_id, $p_name ) {
	if( !worktype_is_unique( $p_project_id, $p_name ) ) {
		trigger_error( ERROR_CATEGORY_DUPLICATE, ERROR );
	}
}

function worktype_add( $p_author_id, $p_project_id, $p_name ) {
	$c_author_id = db_prepare_int($p_author_id);
	$c_project_id = db_prepare_int( $p_project_id );

	if( is_blank( $p_name ) ) {
		error_parameters( 'Work Type' );
		trigger_error( ERROR_EMPTY_FIELD, ERROR );
	}

	worktype_ensure_unique( $p_project_id, $p_name );

	$t_worktype_table = plugin_table( 'type' );

	$query = "INSERT INTO $t_worktype_table
					( author_id, project_id, name, date_submitted, date_updated )
				  VALUES
					( " . db_param() . ', '  . db_param() . ', ' . db_param() . ',' . db_now() . ',' . db_now() . ' )';
	db_query_bound( $query, array( $c_author_id, $c_project_id, $p_name ) );

	# db_query errors on failure so:
	return db_insert_id( $t_worktype_table );
}
?>

<?php	

	/* form_security_validate( 'manage_proj_cat_add' ); */

	auth_reauthenticate();

	$f_author_id = auth_get_current_user_id();
	$f_project_id	= 0; /* gpc_get_int( 'project_id' ); */
	$f_name			= gpc_get_string( 'name' );

	access_ensure_project_level( config_get( 'manage_project_threshold' ), $f_project_id );

	if ( is_blank( $f_name ) ) {
		error_parameters( 'Work Type' );
		trigger_error( ERROR_EMPTY_FIELD, ERROR );
	}

	$t_names = explode( '|', $f_name );
	$t_worktype_count = count( $t_names );

	foreach ( $t_names as $t_name ) {
		if ( is_blank( $t_name ) ) {
			continue;
		}

		$t_name = trim( $t_name );
		if ( worktype_is_unique( $f_project_id, $t_name ) ) {
			worktype_add( $f_author_id, $f_project_id, $t_name );
		} else if ( 1 == $t_worktype_count ) {
			# We only error out on duplicates when a single value was
			#  given.  If multiple values were given, we just add the
			#  ones we can.  The others already exist so it isn't really
			#  an error.

			trigger_error( ERROR_CATEGORY_DUPLICATE, ERROR );
		}
	}

	form_security_purge( 'manage_proj_cat_add' );

	if ( $f_project_id == ALL_PROJECTS ) {
		$t_redirect_url = 'plugin.php?page=Work/config.php'; /* plugin_page('config.php'); */ /* 'manage_proj_page.php';*/
	} else {
		$t_redirect_url = 'manage_proj_edit_page.php?project_id=' . $f_project_id;
	}

	print_header_redirect( $t_redirect_url );
