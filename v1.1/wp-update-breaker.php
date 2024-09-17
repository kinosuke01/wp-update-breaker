<?php
/*
Plugin Name: WP Update Breaker
Description: This plugin breaks the site display when updating. Used for testing.
Version: 1.1
Author: kinosuke01
Plugin URI: https://kinosuke01.github.io/wp-update-breaker/
Update URI: https://kinosuke01.github.io/wp-update-breaker/info.json
*/

require_once 'updater.php';

add_filter('the_content', function($content) {
    $text = <<<'EOT'
    The post has been destroyed. This unexpected outcome occurred due to a conflict caused by a recent plugin update. While the plugin was intended to enhance functionality, it unfortunately introduced errors that led to the corruption of several key elements on the site, including this post.
    
    The plugin in question was designed to add custom styling and additional features to WordPress posts. However, it seems that the update created a conflict with the site's existing theme and other plugins, particularly affecting the post rendering process. As a result, the visual structure of the post was broken, leading to the complete destruction of its content and layout. This has left the post unreadable, with only fragments of its original text remaining visible, if at all.
    
    It's important to note that plugin updates, while necessary for security and performance improvements, can sometimes introduce unforeseen issues. In this case, the plugin's CSS and JavaScript files conflicted with the theme’s existing stylesheets, overwriting key elements and breaking the post’s display logic. Moreover, certain custom fields and metadata were not properly processed during the rendering process, further contributing to the post's collapse.
    
    If you encounter a similar situation, it’s crucial to perform a thorough compatibility check before applying plugin updates. Consider using a staging environment to test updates in a controlled setting, allowing you to identify potential issues before they affect your live site. Additionally, keeping regular backups of your site ensures that you can quickly restore lost content if things go wrong.
    
    In this case, the post can only be recovered by restoring a backup or manually recreating it from scratch. Developers should investigate the plugin’s update logs, reviewing any recent changes that might have caused the issue, and report the bug to the plugin’s author. Meanwhile, reverting to a previous version of the plugin may provide a temporary fix until the issue is resolved.
    EOT;

    return '<p>' . $text . '</p>';
});

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'my-plugin-styles',
        plugin_dir_url(__FILE__) . 'style.css',
        array(),
        '1.0',
        'all'
    );
});
