<?php
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */


/**
 * Returns array of questions based on page number (not updated)
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_questions( $metro_id, $page = 1 ){
	global $wpdb;
	$question_sql = 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_questions
		WHERE page_number = $page
		";
		
	$form_rows = $wpdb->get_results( $question_sql, OBJECT );
	return $form_rows;

}

/**
 * Returns array of saved form data by metro id for the page being built.
 *
 * @since    1.0.0
 * @return 	array
 */
function cc_aha_get_form_data( $metro_id, $page = 1 ){

	global $wpdb;
	 
	//get board data from database
	//$table_name = "wp_aha_assessment_board";
	$form_rows = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_board
		WHERE BOARD_ID = %s
		",
		$metro_id )
		, ARRAY_A
	);
	//print_r( $form_rows );
	return $form_rows;
}

/**
 * Updates board and school database tables with answers from survey.
 *
 * Takes $_POST arrays of [board] and [school] on form submit,
 *	makes sure their values aren't null, false or empty (so we don't overwrite values
 *	that weren't set in the survey) and wpdb->update s the respective table 
 *
 * @since    1.0.0
 * @param 	array
 * @return	
 */
function cc_aha_update_form_data( ){
	
	/* $POST data, for development reference:
	
	[board] => Array
        (
            [2.2.2.1] => 1
            [2.2.2.2] => Describing state promotion things.  at the local level.
            [2.2.4.1] => local
        )
		
	 [school] => Array
        (
            [4206550] => Array
                (
                    [2.2.5.1] => limited
                    [2.2.5.1.1.1] => 
                    [2.2.5.1.3] => textconewago
                )

            [4210710] => Array
                (
                    [2.2.5.1] => other
                    [2.2.5.1.1.1] => 
                    [2.2.5.1.3] => text for gettys
                )
			...
	*/
	
	//Mel isn't sure if this is necessary?
	if ( $_POST['metro_id'] != $_COOKIE['aha_active_metro_id'] ) return 0;

	global $wpdb;
	
	//get our board vars for the wpdb->update statement
	$board_id = $_COOKIE['aha_active_metro_id']; // 'BOARD_ID' column in wp_aha_assessment_board; our WHERE clause
	$board_table_name = $wpdb->aha_assessment_board;
	$board_where = array( 
		'BOARD_ID' => $board_id 
	);
	
	//get have key => value pairs for $_POST['board']!
	$update_board_data = array();
	$update_board_data = $_POST['board'];
	
	//remove null, false and empty values from update_board_data - will not go into database
	//Mel note: is this the correct handling of this data?  Don't overwrite on null form values?
	$update_board_data_notempty = array();
	$update_board_data_notempty = array_filter($update_board_data, "strlen");  //strlen as callback will remove false, empty and null but leave 0
	
	//if we have [board] values set by the form, update the table
	if ( !empty ( $update_board_data_notempty ) ) {
		$num_board_rows_updated = $wpdb->update( $board_table_name, $update_board_data_notempty, $board_where, $format = null, $where_format = null );
	}
	
	//get our school vars for the wpdb->update statement
	$school_table_name = $wpdb->aha_assessment_school;
	
	//get key => value pairs for $_POST['school']!
	$update_school_data = array();
	$update_school_data = $_POST['school'];
	
	//foreach district in survey, update db
	foreach ( $update_school_data as $key => $value ){
		
		$district_id = $key;
		
		//set where clause with this district and board
		$school_where = array(
			'AHA_ID' => $board_id,
			'DIST_ID' => $district_id
		);
		
		//the array in value is the district-specific data
		$update_school_dist_data = $value;
		$update_school_dist_data_notempty();
		$update_school_dist_data_notempty = array_filter($update_school_dist_data, "strlen");
		
		//update the table for this district
		$num_school_rows_updated = $wpdb->update( $school_table_name, $update_school_dist_data_notempty, $school_where, $format = null, $where_format = null );
	
	}
	
	$towrite .= PHP_EOL . '$_POST: ' . print_r($_POST, TRUE);
	$towrite .= 'db write success board: ' . $num_rows_updated;
	$towrite .= 'db write success school: ' . $num_school_rows_updated;
	
	
	//$towrite .= sizeof($update_board_data);
	//$towrite .= sizeof($update_board_data_notempty);
	//$towrite .= print_r($update_board_data_notempty, TRUE);
	$towrite .= print_r($update_school_dist_data_notempty, TRUE);
	//$towrite .= sizeof($update_board_data_notempty);
	$fp = fopen("c:\\xampp\\logs\\aha_log.txt", 'a');
	fwrite($fp, $towrite);
	fclose($fp);
	
	
	//will have to account for school data getting updated as well
	if ( $num_board_rows_updated === FALSE || $num_school_rows_updated === FALSE  ) {
		return false; //we have a problem updating
	} else {
		return ( $num_board_rows_updated ); //num rows on success (wpdb->update returns 0 if no data change), FALSE on no success 
	}

}


/**
 * Returns array of arrays of school district data by metro id.
 *
 * @since    1.0.0
 * @return 	array of arrays
 */
function cc_aha_get_school_data( $metro_id ){
	global $wpdb;
	
	//so we will return some data for the moment
	//$table_name = "wp_aha_assessment_school";
	$form_rows = $wpdb->get_results( 
		$wpdb->prepare( 
		"
		SELECT * 
		FROM $wpdb->aha_assessment_school
		WHERE AHA_ID = %s
		",
		$metro_id )
		, ARRAY_A
	);
	//print_r( $form_rows );
	return $form_rows;
}

