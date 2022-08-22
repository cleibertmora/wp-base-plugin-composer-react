<?php

/**
 * Author: Cleibert Mora
 * Version: 1.0.0
 * Plugin Name: Vic Entreno Plugin
 * Description: Plugin para administración de clientes de entrenador personal
 */


if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'vendor/autoload.php';

use VicEntrenoApp\Plugin;

if ( class_exists( 'VicEntrenoApp\Plugin' ) ) {
    $the_plugin = new Plugin();
}
