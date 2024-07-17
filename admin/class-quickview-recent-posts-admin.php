<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/admin
 * @author     emi1dv <emildv.work@gmail.com>
 */
class Quickview_Recent_Posts_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->set_logger();
	}

	/**
     * Add the admin menu page for plugin settings.
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {
        try {
            add_options_page(
                __('Recent Posts Settings', 'wp-recent-posts'),
                __('Recent Posts', 'wp-recent-posts'),
                'manage_options',
                'wp-recent-posts',
                array($this, 'display_settings_page')
            );
            $this->get_logger()->info('Admin menu page added.');
        } catch (\Exception $e) {
            $this->get_logger()->error('Error adding admin menu page: ' . $e->getMessage());
        }
    }

	/**
     * Register settings for the plugin.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        try {
            register_setting(
                'wp_recent_posts_group',
                'wp_recent_posts_count',
                array(
                    'sanitize_callback' => array($this, 'sanitize_count'),
                    'default' => 5,
                )
            );

            add_settings_section(
                'wp_recent_posts_section',
                __('Recent Posts Settings', 'wp-recent-posts'),
                null,
                'wp-recent-posts'
            );

            add_settings_field(
                'wp_recent_posts_count',
                __('Number of Recent Posts', 'wp-recent-posts'),
                array($this, 'render_count_field'),
                'wp-recent-posts',
                'wp_recent_posts_section'
            );

            $this->get_logger()->info('Settings registered successfully.');
        } catch (\Exception $e) {
            $this->get_logger()->error('Error registering settings: ' . $e->getMessage());
        }
    }

	/**
     * Sanitize the count setting.
     *
     * @since    1.0.0
     * @param    mixed $input The input value.
     * @return   int   Sanitized count.
     */
    public function sanitize_count($input) {
        $sanitized_input = absint($input);
        if ($sanitized_input <= 0) {
            $this->get_logger()->warning('Sanitized count is less than or equal to zero.');
        }
        return $sanitized_input;
    }

	 /**
     * Render the count field.
     *
     * @since    1.0.0
     */
    public function render_count_field() {
        $count = get_option('wp_recent_posts_count', 5);
        ?>
        <input type="number" id="wp_recent_posts_count" name="wp_recent_posts_count" value="<?php echo esc_attr($count); ?>" min="1" step="1" />
        <p class="description"><?php _e('Specify the number of recent posts to display.', 'wp-recent-posts'); ?></p>
        <?php
    }

	 /**
     * Display the settings page.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        try {
            include_once plugin_dir_path(__FILE__) . 'partials/quickview-recent-posts-admin-display.php';
            display_settings_page();
            $this->get_logger()->info('Settings page displayed.');
        } catch (\Exception $e) {
            $this->get_logger()->error('Error displaying settings page: ' . $e->getMessage());
        }
    }

	/**
     * Initialize the logger for the admin area.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_logger() {
        if (class_exists('Monolog\Logger') && class_exists('Monolog\Handler\StreamHandler')) {
            $this->logger = new Monolog\Logger('wp_recent_posts_admin');
            $this->logger->pushHandler(new Monolog\Handler\StreamHandler(plugin_dir_path(__FILE__) . 'logs/admin-error.log', Monolog\Logger::ERROR));
        } else {
            $this->logger = null; // Fallback if Monolog is not available
        }
    }

	/**
     * Get the logger instance.
     *
     * @since     1.0.0
     * @return    Monolog\Logger|null    The logger instance.
     */
    public function get_logger() {
        return $this->logger;
    }

	/**
     * Add hooks for the admin area.
     *
     * @since    1.0.0
     */
    private function add_admin_hooks() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * Register hooks for the settings.
     *
     * @since    1.0.0
     */
    private function register_settings_hooks() {
        add_action('admin_init', array($this, 'register_settings'));
    }

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quickview-recent-posts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quickview-recent-posts-admin.js', array( 'jquery' ), $this->version, false );

	}

}
