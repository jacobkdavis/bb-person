<?php
/**
 * Plugin Name: BB Person
 * Plugin URI: https://github.com/jacobkdavis/bb-person
 * Description: Custom beaver builder module for a person card
 * Version: 1.0.0
 * Author: Jacob Davis
 * Author URI: http://jacobkdavis.com
 * GitHub Plugin URI: jacobkdavis/bb-person
 */
define( 'BB_PERSON_DIR', plugin_dir_path( __FILE__ ) );
define( 'BB_PERSON_URL', plugins_url( '/', __FILE__ ) );

function bb_person_load_module() {
	if ( class_exists( 'FLBuilder' ) ) {
			include('person/person.php');
	}
}
add_action( 'init', 'bb_person_load_module' );
