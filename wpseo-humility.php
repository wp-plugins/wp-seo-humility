<?php
/*
Plugin Name: WP SEO Humility
Description: Removes the hooked action that redirects to WP SEO's About page.
Version: 0.1
License: GPL version 2 or any later version
Author: Donna McMaster
Author URI: http://www.donnamcmaster.com/
*/

/*
Credits:
Kevin Miller for the algorithm and code
Mark Jaquith for the template I "borrowed" from Menu Humility
Participants in https://wordpress.org/support/topic/thank-you-for-updating-wordpress-seo-by-yoast-why-do-i-see-this-10-times-a-day
for the impetus to make it into a plugin
*/

class MCW_WP_SEO_Humility_Plugin {
	static $instance;

	public function __construct() {
		self::$instance =& $this;
		add_action( 'admin_init', array( $this, 'wpseo_silence' ), 16 );
	}

	public function wpseo_silence() {
		$this->remove_filter_by_class_name( 'admin_init', array( 'WPSEO_Admin_Init', 'redirect_to_about_page' ), 15 );
	}

	/**
	* remove_filter_by_class_name
	*
	* Remove actions/filters by class name, function and priority
	*
	* @param string   $tag                The filter hook to which the function to be removed is hooked.
	* @param array    $function_to_remove The name of the class and function which should be removed.
	* @param int      $priority           Optional. The priority of the function. Default 10.
	* @return boolean Whether the function existed before it was removed.
	*/
	public function remove_filter_by_class_name( $tag, $function_to_remove, $priority = 10 ) {
		global $wp_filter;

		if ( isset( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ][ $priority ] ) && is_array( $function_to_remove ) && count( $function_to_remove ) == 2) {
			foreach ( $wp_filter[ $tag ][ $priority ] as $function => $class ) {
				if ( get_class( $class['function'][0] ) == $function_to_remove[0] && strrpos( $function, $function_to_remove[1] ) == strlen( $function ) - strlen( $function_to_remove[1] ) ) {
					remove_filter( $tag, array( $class['function'][0], $function_to_remove[1] ), $priority );
					return true;
				}
			}
		}
		return false;
	}
} // class

// Bootstrap
new MCW_WP_SEO_Humility_Plugin;
