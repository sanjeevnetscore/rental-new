<?php
class RBP_Booking {
    public static function init() {
        // Display booking options on the product loop (shop page)
        add_action('woocommerce_after_shop_loop_item', array(__CLASS__, 'display_booking_options'), 20);
        
        // Display booking options on the single product page
        add_action('woocommerce_single_product_summary', array(__CLASS__, 'display_booking_options_single'), 20);

        // Display booking options in the WooCommerce product edit page (admin side)
        add_action('woocommerce_product_options_general_product_data', array(__CLASS__, 'add_booking_options_to_product_edit_page'));
        add_action('woocommerce_process_product_meta', array(__CLASS__, 'save_booking_options'), 10, 2);
    }

    // Display booking options on the product loop (shop page)
    public static function display_booking_options() {
        if (rbp_is_plugin_enabled()) {
            echo '<div class="booking-options">
                    <h3>' . __('Select Your Rental Option', 'rental-booking-plugin') . '</h3>
                    <p>' . __('Daily, Weekly, Monthly rates available.', 'rental-booking-plugin') . '</p>
                  </div>';
        }
    }

    // Display booking options on the single product page
    public static function display_booking_options_single() {
        global $post;
        if (rbp_is_plugin_enabled()) {
            $daily_rate = get_post_meta($post->ID, '_daily_rate', true);
            $weekly_rate = get_post_meta($post->ID, '_weekly_rate', true);
            $monthly_rate = get_post_meta($post->ID, '_monthly_rate', true);
            
            echo '<div class="single-booking-options">
                    <h3>' . __('Rental Booking Options', 'rental-booking-plugin') . '</h3>
                    <label for="daily_rate">' . __('Daily Rate', 'rental-booking-plugin') . '</label>
                    <input type="number" name="daily_rate" id="daily_rate" value="' . esc_attr($daily_rate) . '">
                    <label for="weekly_rate">' . __('Weekly Rate', 'rental-booking-plugin') . '</label>
                    <input type="number" name="weekly_rate" id="weekly_rate" value="' . esc_attr($weekly_rate) . '">
                    <label for="monthly_rate">' . __('Monthly Rate', 'rental-booking-plugin') . '</label>
                    <input type="number" name="monthly_rate" id="monthly_rate" value="' . esc_attr($monthly_rate) . '">
                  </div>';
        }
    }

    // Add rental booking fields to the WooCommerce product edit page (admin side)
    public static function add_booking_options_to_product_edit_page() {
        woocommerce_wp_text_input(array(
            'id' => '_daily_rate',
            'label' => __('Daily Rate', 'rental-booking-plugin'),
            'desc_tip' => 'true',
            'description' => __('Enter the daily rental rate for this product.', 'rental-booking-plugin'),
            'type' => 'number',
        ));

        woocommerce_wp_text_input(array(
            'id' => '_weekly_rate',
            'label' => __('Weekly Rate', 'rental-booking-plugin'),
            'desc_tip' => 'true',
            'description' => __('Enter the weekly rental rate for this product.', 'rental-booking-plugin'),
            'type' => 'number',
        ));

        woocommerce_wp_text_input(array(
            'id' => '_monthly_rate',
            'label' => __('Monthly Rate', 'rental-booking-plugin'),
            'desc_tip' => 'true',
            'description' => __('Enter the monthly rental rate for this product.', 'rental-booking-plugin'),
            'type' => 'number',
        ));
    }

    // Save rental booking fields when the product is saved
    public static function save_booking_options($post_id) {
        if (isset($_POST['_daily_rate'])) {
            update_post_meta($post_id, '_daily_rate', sanitize_text_field($_POST['_daily_rate']));
        }
        if (isset($_POST['_weekly_rate'])) {
            update_post_meta($post_id, '_weekly_rate', sanitize_text_field($_POST['_weekly_rate']));
        }
        if (isset($_POST['_monthly_rate'])) {
            update_post_meta($post_id, '_monthly_rate', sanitize_text_field($_POST['_monthly_rate']));
        }
    }
}
