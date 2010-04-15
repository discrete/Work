<?php 
function journal_add( $p_bug_id, $p_project_id, $p_worktype_id = 0, $p_manday, $p_user_id = null, $p_send_email = FALSE ) {
	$c_bug_id = db_prepare_int( $p_bug_id );
	$c_project_id = db_prepare_int( $p_project_id );
	$c_worktype_id = db_prepare_int( $p_worktype_id );
	$t_manday = db_prepare_double($p_manday);

	# get user information
	if( $p_user_id === null ) {
		$c_user_id = auth_get_current_user_id();
	} else {
		$c_user_id = db_prepare_int( $p_user_id );
	}
	
	$t_journal_table = plugin_table( 'journal' );
	
	# insert bugnote text
	$query = 'INSERT INTO ' . $t_journal_table . 
				' ( bug_id, author_id, project_id, work_id, man_day, date_submitted, date_updated) VALUES ( ' 
				. db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', '
				. db_now() . ',' . db_now() . ' )';
	db_query_bound( $query, Array( $c_bug_id, $c_user_id, $c_project_id, $c_worktype_id, $t_manday ) );

	# retrieve bugnote text id number
	$t_manday_id = db_insert_id( $t_journal_table );

	# update bug last updated
	bug_update_date( $p_bug_id );

	# log new bug
	/* history_log_event_special( $p_bug_id, BUGNOTE_ADDED, bugnote_format_id( $t_bugnote_id ) ); */

	# Event integration
	/* event_signal( 'EVENT_BUGNOTE_ADD', array( $p_bug_id, $t_bugnote_id ) ); */

	# only send email if the text is not blank, otherwise, it is just recording of time without a comment.
	if( TRUE == $p_send_email && !is_blank( $t_manday ) ) {
		email_bugnote_add( $p_bug_id );
	}

	return $t_manday_id;
}
?>
<?php
	/* form_security_validate( 'journal_add' ); */

	$f_bug_id		= gpc_get_int( 'bug_id' );
	$f_worktype_id		= gpc_get_int( 'worktype_id' );
	$f_manday	= gpc_get_string( 'man_day' );

	$t_bug = bug_get( $f_bug_id, true );
	if( $t_bug->project_id != helper_get_current_project() ) {
		# in case the current project is not the same project of the bug we are viewing...
		# ... override the current project. This to avoid problems with categories and handlers lists etc.
		$g_project_override = $t_bug->project_id;
	}

	if ( bug_is_readonly( $f_bug_id ) ) {
		error_parameters( $f_bug_id );
		trigger_error( ERROR_BUG_READ_ONLY_ACTION_DENIED, ERROR );
	}

	access_ensure_bug_level( config_get( 'add_bugnote_threshold' ), $f_bug_id );

	// We always set the note time to BUGNOTE, and the API will overwrite it with TIME_TRACKING
	// if $f_time_tracking is not 0 and the time tracking feature is enabled.
	$t_bugnote_id = journal_add( $f_bug_id, $t_bug->project_id, $f_worktype_id, $f_manday );
    if ( !$t_bugnote_id ) {
        error_parameters( lang_get( 'bugnote' ) );
        trigger_error( ERROR_EMPTY_FIELD, ERROR );
    }

	/* form_security_purge( 'journal_add' ); */

	print_successful_redirect_to_bug( $f_bug_id );