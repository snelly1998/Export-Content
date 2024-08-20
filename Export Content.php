<?php

/**
 * Plugin Name: Export Content
 * Description: Custom Export.
 * Version: 1.1.0
 * Author: Jared Snell
 **/

ob_start();
// Create table
function wp_create_database_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'toolkit_tracking';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name text NOT NULL,
        company text NOT NULL,
        email text NOT NULL,
        resource text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// Register table creation on plugin activation
register_activation_hook(__FILE__, 'wp_create_database_table');

// Delete table on plugin deactivation
function wp_learn_delete_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'toolkit_tracking';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

register_deactivation_hook(__FILE__, 'wp_learn_delete_table');

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Admin Page.php';

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Shortcode.php';

add_shortcode('page_redirect', 'page_redirect_shortcode');