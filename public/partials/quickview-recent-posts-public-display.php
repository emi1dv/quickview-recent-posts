<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/emi1dv
 * @since      1.0.0
 *
 * @package    Quickview_Recent_Posts
 * @subpackage Quickview_Recent_Posts/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="quickview-recent-posts-grid">
    <?php if ($query->have_posts()) : ?>
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="quickview-recent-post">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="quickview-post-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="quickview-post-content">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php _e('No recent posts found.', 'quickview-recent-posts'); ?></p>
    <?php endif; ?>
</div>

