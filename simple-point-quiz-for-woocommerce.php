<?php
/**
 * @link              https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * @since             1.0.0
 * @package           AWoo_PQ
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Point Quiz For Woocommerce
 * Plugin URI:        https://bitbucket.org/allouise/simple-point-quiz-for-woocommerce
 * Description:       Simple Woocommerce Product Suggestion via Point System by Taking Quizzes
 * Version:           1.0.0
 * Author:            Allyson Flores
 * Author URI:        http://allysonflores.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-point-quiz-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 4.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AWOOPQ_SPQ_VERSION', '1.0.0' );

/*
 * Check for Woocommerce Dependencies
 */
function dependency_alf_spq_woocommerce_point_quiz( $cons = false ){
	if ( !class_exists( 'woocommerce' ) || !defined('WC_VERSION') /*|| ( defined('WC_VERSION') && WC_VERSION < 3.0 )*/ ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );

		if( $cons === true ){
			wp_die( __('<p><strong>Simple Woocommerce Point Quiz:</strong> Please make sure at least <strong>Woocommerce <u>3.0</u></strong> is installed & activated<p>').'<a href="'.get_admin_url().'plugins.php">'.__('Go Back').'</a>' );
		}else{
			echo '<div class="error" style="-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif">'.__('<p><strong>Simple Woocommerce Point Quiz:</strong> Please make sure at least <strong>Woocommerce <u>3.0</u></strong> is installed & activated<p>').'</div>';
		}
	}
}
add_action( 'plugins_loaded', 'dependency_alf_spq_woocommerce_point_quiz');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-point-quiz-for-woocommerce-activator.php
 */
function activate_alf_spq_woocommerce_point_quiz() {
	dependency_alf_spq_woocommerce_point_quiz(true);
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-point-quiz-for-woocommerce-activator.php';
	AWoo_PQ_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-point-quiz-for-woocommerce-deactivator.php
 */
function deactivate_alf_spq_woocommerce_point_quiz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-point-quiz-for-woocommerce-deactivator.php';
	AWoo_PQ_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_alf_spq_woocommerce_point_quiz' );
register_deactivation_hook( __FILE__, 'deactivate_alf_spq_woocommerce_point_quiz' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-point-quiz-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_alf_spq_woocommerce_point_quiz() {

	$plugin = new AWoo_PQ();
	$plugin->run();

}
run_alf_spq_woocommerce_point_quiz();
