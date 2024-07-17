<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 * @author     emi1dv <emildv.work@gmail.com>
 */
class Quickview_Recent_Posts_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('wp_recent_posts_count');
	}

}
