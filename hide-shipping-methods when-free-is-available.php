<?php
/*
Plugin Name: Hide Shipping Methods when free is available
Plugin URI: https://nubesti.com/
Description: Hides other shipping methods in the cart and at checkout when free shipping is available.
Version: 1.0
Author: Nubesti
Author URI: https://profiles.wordpress.org/nubesti/
*/

// Function to hide shipping methods when free shipping is available
function hide_shipping_methods_when_free_shipping( $rates, $package ) {
    if ( 'yes' !== get_option( 'hide_free_shipping_enable' ) ) {
        return $rates;
    }

    $free_shipping = array();

    foreach ( $rates as $rate_id => $rate ) {
        if ( 'free_shipping' === $rate->method_id ) {
            $free_shipping[ $rate_id ] = $rate;
            break;
        }
    }

    return ! empty( $free_shipping ) ? $free_shipping : $rates;
}

// Add the function to WooCommerce filters
add_filter( 'woocommerce_package_rates', 'hide_shipping_methods_when_free_shipping', 100, 2 );

// Function to create the admin settings option to enable or disable the feature
function hide_free_shipping_settings( $settings ) {
    $settings[] = array(
        'title'   => __( 'Hide Free Shipping', 'hide-free-shipping' ),
        'type'    => 'title',
        'id'      => 'hide_free_shipping',
    );

    $settings[] = array(
        'title'   => __( 'Enable', 'hide-free-shipping' ),
        'desc'    => __( 'Hide other shipping methods when free shipping is available', 'hide-free-shipping' ),
        'id'      => 'hide_free_shipping_enable',
        'default' => 'yes',
        'type'    => 'checkbox',
    );

    $settings[] = array(
        'type' => 'sectionend',
        'id'   => 'hide_free_shipping',
    );

    return $settings;
}
add_filter( 'woocommerce_shipping_settings', 'hide_free_shipping_settings' );
