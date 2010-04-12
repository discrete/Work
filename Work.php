<?php
# Copyright (C) 2008-2010 John Reese, LeetCode.net
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.

require_once( config_get_global( 'class_path' ) . 'MantisPlugin.class.php' );

/**
 * Creates an extensible API for integrating source control applications
 * with the Mantis bug tracker software.
 */ 
class WorkPlugin extends MantisPlugin {
	function register() {
		$this->name = 'Work';
		$this->description = 'We can manage time very well';

		$this->version = '0.2';
		$this->requires = array(
			'MantisCore' => '1.2.0',
		);

		$this->author	= 'Joohong Kim';
		$this->contact	= 'joohong.kim@access-company.com';
		$this->url		= 'http://www.access-company.com';
	}
	
	function config() {
		return array();	
	}

	function event() {
	
	}
	
	function hooks() {
		return array(
			'EVENT_VIEW_BUG_EXTRA'		=> 'list_unitwork',
		);
	}
	
	function schema() {
		return array(
			array ( 'CreateTableSQL',
				array( plugin_table( 'type' ), "
					id				I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
					author_id		I		NOTNULL UNSIGNED DEFAULT '0',
					project_id		I		NOTNULL UNSIGNED DEFAULT '0',
					name			varchar(128)	NOTNULL,
					date_submitted	T		NOTNULL,
					date_updated	T		NOTNULL
				" )
			),
			array( 'CreateTableSQL',
				array( plugin_table( 'journal' ), "
					id				I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
					bug_id			I		NOTNULL UNSIGNED DEFAULT '0',
					author_id		I		NOTNULL UNSIGNED DEFAULT '0',
					project_id		I		NOTNULL UNSIGNED DEFAULT '0',
					work_id			I		NOTNULL UNSIGNED DEFAULT '0',
					man_day			I		NOTNULL UNSIGNED DEFAULT '0',
					date_submitted	T		NOTNULL,
					date_updated	T		NOTNULL
				" )
			),
		);
	}
	
	function list_unitwork($p_event, $p_bug_id){
		include('Work_view_inc.php');
		include('Work_add_inc.php');
	} #end of display_work
}