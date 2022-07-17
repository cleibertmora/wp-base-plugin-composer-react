<?php 
/**
 *
 * @package ClinicManagerWP
 */

namespace ClinicManagerWP;

class Plugin {
		
	public function __construct()
	{
		add_action( 'init', array($this,'register_shortcodes') );
	}
	
	public function register_shortcodes()
	{
		add_shortcode( 'cmwpautoagendamiento', array($this, 'autoagendamiento') );
	}

	public function autoagendamiento($atts=array(), $content=null)
	{
		if ( !is_admin() ): 
			wp_enqueue_style('clinic-manager-wp-styles', plugins_url( '../assets/build/frontend.css', __FILE__ ));
			wp_enqueue_script( 'clinic-manager-wp-front-end', plugins_url( '../assets/build/frontend.js', __FILE__ ), array('wp-element'), '1.0', true );
		endif;

		ob_start(); ?>
			<div id="clinic-manager-wp-autoagendamiento"></div>
		<?php return ob_get_clean();
	}

	

}
