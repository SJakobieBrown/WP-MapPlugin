<?php
/**
 * Plugin Name: Custom Map Plugin
 * Description: Displays a Google Map with a marked address.
 * Version: 1.0
 * Author: Samuel Brown
 */

// Enqueue the Google Maps API
function google_map_plugin_enqueue_scripts() {
    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAg_aFky8jQ82Zp2k6DeKkSA-3nY3P9isw', array(), null, false );
}
add_action( 'wp_enqueue_scripts', 'google_map_plugin_enqueue_scripts' );

// Create a shortcode for the Google Map
function google_map_plugin_shortcode( $atts ) {
    // Get the attributes passed to the shortcode
    $atts = shortcode_atts( array(
        'address' => '',
        'zoom' => 15,
        'height' => 400
    ), $atts );
    
    // Get the address from the attributes
    $address = $atts['address'];
    
    // Create a unique ID for the map container
    $map_id = 'google-map-plugin-' . wp_generate_password( 8, false );
    
    // Output the map container and script
    $output = '<div id="' . $map_id . '" style="height: ' . $atts['height'] . 'px;"></div>';
    $output .= '<script>';
    $output .= 'function initMap() {';
    $output .= 'var geocoder = new google.maps.Geocoder();';
    $output .= 'geocoder.geocode({' . "address: '" . $address . "'" . '}, function(results, status) {';
    $output .= 'if (status === "OK") {';
    $output .= 'var map = new google.maps.Map(document.getElementById("' . $map_id . '"), {';
    $output .= 'center: results[0].geometry.location,';
    $output .= 'zoom: ' . $atts['zoom'] . '';
    $output .= '});';
    $output .= 'var marker = new google.maps.Marker({';
    $output .= 'map: map,';
    $output .= 'position: results[0].geometry.location,';
    $output .= 'title: "' . $address . '"';
    $output .= '});';
    $output .= '} else {';
    $output .= 'console.log("Geocode was not successful for the following reason: " + status);';
    $output .= '}';
    $output .= '});';
    $output .= '}';
    $output .= 'initMap();';
    $output .= '</script>';
    
    return $output;
}
add_shortcode( 'google-map', 'google_map_plugin_shortcode' );
