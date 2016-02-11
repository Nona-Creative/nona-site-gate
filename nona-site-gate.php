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

$_version = '1.0.0';
$_token = 'nona_site_gate';
$dir = plugin_dir_path( __FILE__ );
$assets_dir = $dir . 'assets';
$assets_url = plugin_dir_url( __FILE__) . 'assets/';
$script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

// Load plugin class files
require_once( 'includes/nona-site-gate-options.php' );
require_once( 'includes/functions.php' );
