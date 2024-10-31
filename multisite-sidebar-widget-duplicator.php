<?php
/*
Plugin Name: Zedna Multisite Sidebar Widget Duplicator
Plugin URI: https://profiles.wordpress.org/zedna#content-plugins
Description: Duplicate of all widgets in master site for each newly created sub-site.
Version: 1.0
Author: Radek Mezulanik
Author URI: https://www.mezulanik.cz
License: GPL2
*/

add_action("wpmu_new_blog", "wpmu_zedna_sidebar_widget_duplicator");

function wpmu_zedna_sidebar_widget_duplicator( $blog_id, $uid = null, $pwd = null, $s = null, $m = null ) {
global $wpdb;

//Get all widgets
$widgetcopy = array_flip( array( 'active_plugins', 'sidebars_widgets' ));
$w = $wpdb->get_results("select option_name from {$wpdb->prefix}options where option_name like 'widget_%'");
foreach( $w as $k => $v ) {
    $widgetcopy[$v->option_name] = '';
}

//Select master site
switch_to_blog( 1 );

//Get options
foreach( $widgetcopy as $k => $v ) {
    $widgetcopy[$k] = get_option( $k );
}

//Select current sub-site
switch_to_blog( $blog_id );

//Update widgets in options
foreach( $widgetcopy as $k => $v ) {
    delete_option( $k );
    add_option( $k, $v, '', 'yes' );
}
}
