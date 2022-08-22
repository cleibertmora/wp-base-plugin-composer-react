<?php 
/**
 *
 * @package WP_VicEntrenoPlugin
 */
namespace VicEntrenoApp;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Plugin {
		
	public function __construct()
	{
		new FrontEnd();
	}
}
