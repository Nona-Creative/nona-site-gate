<?php
/*
 * Plugin Name: Nona Site Gate
 * Version: 1.0
 * Plugin URI: http://leogopal.com/
 * Description: Simple Site Gate Plugin to restrict a users access to the site.
 * Author: Leo Gopal, Nona Creative
 * Author URI: http://leogopal.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: nona-site-gate
 * Domain Path: /lang/
 *
 * @package WordPress
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-nona-site-gate.php' );
require_once( 'includes/class-nona-site-gate-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-nona-site-gate-admin-api.php' );


/**
 * Returns the main instance of Nona_Site_Gate to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Nona_Site_Gate
 */
function Nona_Site_Gate () {
	$instance = Nona_Site_Gate::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Nona_Site_Gate_Settings::instance( $instance );
	}

	return $instance;
}

Nona_Site_Gate();
