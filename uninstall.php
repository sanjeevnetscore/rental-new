<?php
// If this file is accessed directly, abort.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

// Delete the options from the database
delete_option('netscore_enable_rental_booking');
