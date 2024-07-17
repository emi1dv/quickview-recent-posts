<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/public
 * @author     emi1dv <emildv.work@gmail.com>
 */
class Quickview_Recent_Posts_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quickview_Recent_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quickview_Recent_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quickview-recent-posts-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quickview_Recent_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quickview_Recent_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quickview-recent-posts-public.js', array( 'jquery' ), $this->version, false );

	}

	public function render_recent_posts_shortcode($atts) {
        try {
            $atts = shortcode_atts(
                array(
                    'count' => get_option('wp_recent_posts_count', 5), // Use default count from settings if not provided
                ),
                $atts,
                'recent_posts'
            );

            $query = new WP_Query(array(
                'posts_per_page' => $atts['count'],
                'post_status'    => 'publish',
            ));

            ob_start();

            // Include the partial file and pass the query object to it
            include plugin_dir_path(__FILE__) . 'partials/quickview-recent-posts-public-display.php';

            wp_reset_postdata();

            return ob_get_clean();
        } catch (\Exception $e) {
            $this->get_logger()->error('Error rendering recent posts shortcode: ' . $e->getMessage());
            return '<p>' . __('An error occurred while displaying recent posts.', 'quickview-recent-posts') . '</p>';
        }
    }

}
