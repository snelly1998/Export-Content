<?php
function page_redirect_shortcode($atts) {
    global $wpdb;

    // Define default attributes
    $atts = shortcode_atts(
        array(
            'userName' => 'userName', // Default cookie name
            'userEmail' => 'userEmail',
            'userCompany' => 'userCompany',
            'resource' => '' // Default URL attribute
        ),
        $atts,
        'page_redirect'
    );

    // Retrieve cookie values
    $name_value = isset($_COOKIE[$atts['userName']]) ? sanitize_text_field($_COOKIE[$atts['userName']]) : 'Not found';
    $email_value = isset($_COOKIE[$atts['userEmail']]) ? sanitize_email($_COOKIE[$atts['userEmail']]) : 'Not found';
    $company_value = isset($_COOKIE[$atts['userCompany']]) ? sanitize_text_field($_COOKIE[$atts['userCompany']]) : 'Not found';

    // Extract the redirect URL from query parameters
    $redirect_url = isset($_GET['redirect']) ? esc_url($_GET['redirect']) : $atts['url'];

    // Insert data into toolkit_tracking table
    $table_name = $wpdb->prefix . 'toolkit_tracking';
    $result = $wpdb->insert(
        $table_name,
        array(
            'name' => $name_value,
            'email' => $email_value,
            'company' => $company_value,
            'time' => current_time('mysql'),
            'resource' => $redirect_url,
        )
    );
    // Handle URL attribute
    if (!empty($redirect_url)) {
        wp_redirect($redirect_url);
        exit;
    }
}
?>