<?php
/*
Plugin Name: CC American Heart Association Extras
Description: Adds extras to the AHA group space
Version: 1.0
*/
/**
 * CC American Heart Association Extras
 *
 * @package   CC American Heart Association Extras
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

function cc_aha_extras_class_init(){
	// Get the class fired up
	// Helper and utility functions
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-functions.php' );
	// Template-y functions
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-template-tags.php' );
	// Template functions for the form
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-survey-template-tags.php' );
	// The main class
	require_once( dirname( __FILE__ ) . '/includes/cc-aha-extras.php' );
	add_action( 'bp_include', array( 'CC_AHA_Extras', 'get_instance' ), 21 );
}
add_action( 'bp_include', 'cc_aha_extras_class_init' );

/* Only load the component if BuddyPress is loaded and initialized. */
function startup_aha_extras_group_extension_class() {
	require( dirname( __FILE__ ) . '/includes/class-bp-group-extension.php' );
}
add_action( 'bp_include', 'startup_aha_extras_group_extension_class', 24 );