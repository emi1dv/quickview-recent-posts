<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 * @author     emi1dv <emildv.work@gmail.com>
 */
class Quickview_Recent_Posts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'quickview-recent-posts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
