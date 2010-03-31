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

		$this->version = '0.1';
		$this->requires = array(
			'MantisCore' => '1.2.0',
		);

		$this->author	= 'Joohong Kim';
		$this->contact	= 'joohong.kim@access-company.com';
		$this->url		= 'http://www.access-company.com';
	}

	function event() {
	
	}
	
	function hooks() {
		return array(
			'EVENT_VIEW_BUG_EXTRA'		=> 'view',
		);
	}
	
	function schema() {
		
	}
	
	function view($p_event, $p_bug_id){
		require_once('work.ViewAPI.php');
		
		

	} #end of display_work
}