<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 * @author     emi1dv <emildv.work@gmail.com>
 */
class Quickview_Recent_Posts_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('wp_recent_posts_count', 10);
	}

}
