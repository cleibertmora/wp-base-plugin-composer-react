<?php

/**
 * Author: Cleibert Mora
 * Version: 1.0.0
 * Plugin Name: Clinic Manager Para WordPress
 * Description: Plugin para hacer sitios web de centros clínicos y administrarlos
 */


if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'vendor/autoload.php';

use ClinicManagerWP\Plugin;

if ( class_exists( 'ClinicManagerWP\Plugin' ) ) {
    $the_plugin = new Plugin();
}
