<?php
/**
 * Plugin Name: Netscore Rental Booking
 * Description: A plugin to add a rental booking system with rates and inventory table.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: rental-booking-plugin
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load necessary files
include_once plugin_dir_path( __FILE__ ) . 'includes/class-rbp-booking.php';

// Initialize the plugin
add_action('plugins_loaded', array('RBP_Booking', 'init'));

// Check if plugin is enabled
function rbp_is_plugin_enabled() {
    return get_option('netscore_enable_rental_booking') === 'yes';
}

// Add admin menu and settings
add_action('admin_menu', 'rbp_add_admin_menu');
function rbp_add_admin_menu() {
    // Add main menu item
    add_menu_page(
        __('Netscore Rental Booking', 'rental-booking-plugin'),
        __('Netscore Rental', 'rental-booking-plugin'),
        'manage_options', 
        'netscore-rental-booking', 
        'rbp_admin_page', // Ensure this is defined below
        'dashicons-businessperson', 
        56
    );

    // Add submenu for settings
    add_submenu_page(
        'netscore-rental-booking',
        __('Settings', 'rental-booking-plugin'),
        __('Settings', 'rental-booking-plugin'),
        'manage_options',
        'netscore-rental-settings',
        'rbp_settings_page'
    );
}

// Callback function for the main menu page (Dashboard)
function rbp_admin_page() {
    echo '<div class="wrap">';
    echo '<h1>' . __('Netscore Rental Booking Dashboard', 'rental-booking-plugin') . '</h1>';
    echo '<p>' . __('Welcome to the Netscore Rental Booking plugin. Here you can configure your rental settings and manage bookings.', 'rental-booking-plugin') . '</p>';
    echo '</div>';
}

// Callback function for the settings page
function rbp_settings_page() {
    echo '<div class="wrap">';
    echo '<h1>' . __('Netscore Rental Settings', 'rental-booking-plugin') . '</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('rbp_settings_group');
    do_settings_sections('netscore-rental-settings');
    submit_button();
    echo '</form>';
    echo '</div>';
}

// Register settings for enabling booking options
add_action('admin_init', 'rbp_register_settings');
function rbp_register_settings() {
    register_setting('rbp_settings_group', 'netscore_enable_rental_booking');
    add_settings_section('rbp_general_settings', __('General Settings', 'rental-booking-plugin'), null, 'netscore-rental-settings');
    add_settings_field('netscore_enable_rental_booking', __('Enable Rental Booking', 'rental-booking-plugin'), 'rbp_enable_rental_booking_callback', 'netscore-rental-settings', 'rbp_general_settings');
}

// Callback for the checkbox to enable rental booking
function rbp_enable_rental_booking_callback() {
    $value = get_option('netscore_enable_rental_booking', 'no');
    echo '<input type="checkbox" id="netscore_enable_rental_booking" name="netscore_enable_rental_booking" value="yes" ' . checked($value, 'yes', false) . '/>';
    echo '<label for="netscore_enable_rental_booking">' . __('Enable rental booking system for products', 'rental-booking-plugin') . '</label>';
}

// Plugin activation hook
register_activation_hook(__FILE__, 'rbp_plugin_activation');
function rbp_plugin_activation() {
    update_option('netscore_enable_rental_booking', 'no');
}

// Plugin deactivation hook
register_deactivation_hook(__FILE__, 'rbp_plugin_deactivation');
function rbp_plugin_deactivation() {
    delete_option('netscore_enable_rental_booking');
}
