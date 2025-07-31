<?php
/**
 * Plugin Name: Maintenance Mode Toggle
 * Description: Adds a toggle in settings to enable/disable maintenance mode.
 * Version: 1.0
 * Author: Cryptoball cryptoball7@gmail.com
 */

// Add setting field to General Settings
add_action('admin_init', function() {
    add_settings_field(
        'maintenance_mode_toggle',
        'Maintenance Mode',
        'maintenance_mode_toggle_field_html',
        'general'
    );

    register_setting('general', 'maintenance_mode_toggle', [
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default' => false,
    ]);
});

function maintenance_mode_toggle_field_html() {
    $value = get_option('maintenance_mode_toggle', false);
    echo '<input type="checkbox" id="maintenance_mode_toggle" name="maintenance_mode_toggle" value="1" ' . checked(1, $value, false) . ' />';
    echo '<label for="maintenance_mode_toggle"> Enable Maintenance Mode</label>';
}

// Show maintenance message to non-logged-in users
add_action('template_redirect', function() {
    if (get_option('maintenance_mode_toggle') && !current_user_can('manage_options')) {
        wp_die(
            '<h1>Website Under Maintenance</h1><p>Please check back soon.</p>',
            'Maintenance Mode',
            ['response' => 503]
        );
    }
});
