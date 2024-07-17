<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 */

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/includes
 * @author     emi1dv <emildv.work@gmail.com>
 */
class Quickview_Recent_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Quickview_Recent_Posts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'QUICKVIEW_RECENT_POSTS_VERSION' ) ) {
			$this->version = QUICKVIEW_RECENT_POSTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'quickview-recent-posts';
		$this->load_dependencies();
		$this->set_logger();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Quickview_Recent_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - Quickview_Recent_Posts_i18n. Defines internationalization functionality.
	 * - Quickview_Recent_Posts_Admin. Defines all hooks for the admin area.
	 * - Quickview_Recent_Posts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		try {
			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quickview-recent-posts-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quickview-recent-posts-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-quickview-recent-posts-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-quickview-recent-posts-public.php';
			
			/**
			 * Connecting to the library for logging.
			 */

			require_once plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';


			$this->loader = new Quickview_Recent_Posts_Loader();
		
		} catch (\Exception $e) {
			$this->get_logger()->error('Error loading dependencies: ' . $e->getMessage());
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Quickview_Recent_Posts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Quickview_Recent_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		try {

			$plugin_admin = new Quickview_Recent_Posts_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

			$this->get_logger()->info('Admin hooks have been defined successfully.');

		} catch (\Exception $e) {
			$this->get_logger()->error('Error defining admin hooks: ' . $e->getMessage());
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		try {
			$plugin_public = new Quickview_Recent_Posts_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_shortcode('recent_posts', $plugin_public, 'render_recent_posts_shortcode');

			$this->get_logger()->info('Public hooks have been defined successfully.');
		} catch(\Exception $e) {
			$this->get_logger()->error('Error defining public hooks: ' . $e->getMessage());
		}
	}

	/**
	 * Render the recent posts shortcode.
	 *
	 * @since    1.0.0
	 * @param    array $atts Shortcode attributes.
	 * @return   string     The HTML output for the recent posts.
	*/
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
	
			if ($query->have_posts()) {
				echo '<ul>';
				while ($query->have_posts()) {
					$query->the_post();
					?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php
				}
				echo '</ul>';
			} else {
				_e('No recent posts found.', 'wp-recent-posts');
			}
	
			wp_reset_postdata();
	
			return ob_get_clean();
		} catch (\Exception $e) {
			$this->get_logger()->error('Error rendering recent posts shortcode: ' . $e->getMessage());
			return '<p>' . __('An error occurred while displaying recent posts.', 'wp-recent-posts') . '</p>';
		}
    }

	/**
	 * Initialize the logger.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_logger() {
		$this->logger = new Monolog\Logger('emi1dv_wp_recent_posts');
        $this->logger->pushHandler(new Monolog\Handler\StreamHandler(plugin_dir_path( __FILE__ ) . 'logs/plugin.log', Monolog\Logger::DEBUG));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Quickview_Recent_Posts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get the logger instance.
	 *
	 * @since     1.0.0
	 * @return    LoggerInterface    The logger instance.
	 */
	public function get_logger() {
		return $this->logger;
	}

}
