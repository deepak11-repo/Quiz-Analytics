<?php
/*
Plugin Name: Quiz Insights
Description: The Quiz Insights plugin enhances your LearnDash experience by providing detailed analytics and insights into quiz performance. Gain valuable understanding of learners' progress and comprehension through comprehensive reports
Version: 1.0
Author: Deepak
*/

require_once plugin_dir_path(__FILE__) .'includes/admin/admin.php';

function enqueue_custom_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue DataTables script
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', array('jquery'), null, true);

    // Enqueue DataTables Buttons script
    wp_enqueue_script('datatables-buttons', 'https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js', array('datatables'), '2.3.2', true); // Ensure Buttons script version is compatible with DataTables version

    // Enqueue DataTables Buttons HTML5 extension script
    wp_enqueue_script('datatables-buttons-html5', 'https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js', array('datatables-buttons'), '2.3.2', true);

    // Enqueue DataTables CSS
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');

    // Enqueue DataTables Buttons CSS
    wp_enqueue_style('datatables-buttons-css', 'https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css');

    // Enqueue JSZip library
    wp_enqueue_script('jszip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js', array(), '3.10.1', true);

    //Custom CSS
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'custom-style', $plugin_url . 'assets/css/style.css');

    // Enqueue the custom script
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery', 'datatables'), null, true);

    // Create a nonce
    $ajax_nonce = wp_create_nonce('fetch_student_data_nonce');

    // Localize the script with the admin-ajax.php URL and nonce
    wp_localize_script('custom-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => $ajax_nonce
    ));    
    
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts');
