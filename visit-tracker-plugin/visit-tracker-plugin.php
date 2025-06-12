<?php
/**
 * Plugin Name:       Visit Tracker
 * Plugin URI:        https://example.com/plugins/visit-tracker/
 * Description:       Tracks site visits and displays weekly/monthly counts via shortcodes.
 * Version:           1.0.0
 * Author:            Jules AI Assistant
 * Author URI:        https://example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       visit-tracker
 * Domain Path:       /languages
 */

// Function to create the database table on plugin activation
function vt_create_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'site_visits';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        visit_id mediumint(9) NOT NULL AUTO_INCREMENT,
        visit_timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        ip_address VARCHAR(100) NOT NULL,
        PRIMARY KEY  (visit_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'vt_create_database_table');

// Function to record site visits
function vt_record_visit() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'site_visits';

    // Basic IP address retrieval (can be enhanced for accuracy behind proxies)
    $ip_address = '0.0.0.0'; // Default IP
    if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        // Handle X-Forwarded-For header if present (comma-separated list)
        $ips = explode( ',', sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
        $ip_address = trim( $ips[0] ); // Get the first IP in the list
    } elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip_address = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
    }

    // Validate IP address format (optional but good practice)
    if ( !filter_var($ip_address, FILTER_VALIDATE_IP) ) {
        $ip_address = '0.0.0.0'; // Fallback to default if IP is not valid
    }


    $wpdb->insert(
        $table_name,
        array(
            'visit_timestamp' => current_time('mysql'),
            'ip_address' => $ip_address,
        )
    );
}
add_action('wp_loaded', 'vt_record_visit');

// Prevent direct file access
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Shortcode to display weekly visit count
function vt_weekly_visits_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'site_visits';
    $current_time = current_time('mysql');
    $one_week_ago = date('Y-m-d H:i:s', strtotime('-7 days', strtotime($current_time)));

    $weekly_visits = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(visit_id) FROM $table_name WHERE visit_timestamp >= %s",
            $one_week_ago
        )
    );

    return "This week's visits: " . intval($weekly_visits);
}
add_shortcode('site_visits_weekly', 'vt_weekly_visits_shortcode');

// Shortcode to display monthly visit count
function vt_monthly_visits_shortcode() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'site_visits';
    $current_month_start = date('Y-m-01 00:00:00', strtotime(current_time('mysql')));

    $monthly_visits = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(visit_id) FROM $table_name WHERE visit_timestamp >= %s",
            $current_month_start
        )
    );

    return "This month's visits: " . intval($monthly_visits);
}
add_shortcode('site_visits_monthly', 'vt_monthly_visits_shortcode');

// Function to remove the database table on plugin deactivation
function vt_remove_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'site_visits';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'vt_remove_database_table');
