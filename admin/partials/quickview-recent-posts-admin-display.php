<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/admin/partials
 */

function display_settings_page() {
    $total_posts = wp_count_posts()->publish;
    ?>
    <div class="wrap">
        
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_recent_posts_group');
            do_settings_sections('wp-recent-posts');
            submit_button();
            ?>
        </form>
        <hr />
        <h2><?php _e('Shortcode Example', 'wp-recent-posts'); ?></h2>
        <p><?php _e('Use the following shortcode to display recent posts:', 'wp-recent-posts'); ?></p>
        <pre>[recent_posts]</pre>
        <p><?php _e('You can change the number of posts by setting the "count" attribute, e.g.:', 'wp-recent-posts'); ?></p>
        <pre>[recent_posts count="10"]</pre>
        <hr />
        <h2><?php _e('Total Posts', 'wp-recent-posts'); ?></h2>
        <p><?php printf(__('There are a total of %d posts on your site.', 'wp-recent-posts'), $total_posts); ?></p>
    </div>
    <?php
}

