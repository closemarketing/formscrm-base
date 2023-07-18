<?php
/**
 * Plugin Name: FormsCRM CRMNAME Addon
 * Plugin URI:  https://close.marketing/plugins/formscrm-crmname/
 * Description: Adds customize to CRMNAME.
 * Version:     1.0
 * Author:      Closemarketing
 * Author URI:  https://close.marketing/
 * Text Domain: formscrm-crmname
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     WordPress
 * @author      Closemarketing
 * @copyright   2021 Closemarketing
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      formscrm_crmname
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

add_action( 'plugins_loaded', 'formscrm_crmname_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function formscrm_crmname_plugin_init() {
	load_plugin_textdomain( 'formscrm-crmname', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_filter(
	'formscrm_choices',
	function( $choices ) {
		// Add a new option to the styles array.
		$choices[] = array(
			'label' => 'CRMNAME',
			'value' => 'crmname',
		);

		// Return the array of style options.
		return $choices;
	}
);


add_filter(
	'formscrm_crmlib_path',
	function( $choices ) {
		// Add a new option to the styles array.

		$choices['crmname'] = plugin_dir_path( __FILE__ ) . 'includes/class-crmlib-crmname.php';

		// Return the array of paths.
		return $choices;
	}
);

add_filter(
	'formscrm_dependency_apipassword',
	function( $choices ) {
		// Add a new option to the styles array.

		$choices[] = 'crmname';

		// Return the array of paths.
		return $choices;
	}
);

add_action( 'admin_init', 'formscrm_crmname_dependency' );
/**
 * Checks depency
 *
 * @return void
 */
function formscrm_crmname_dependency() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'formscrm/formscrm.php' ) ) {
		add_action( 'admin_notices', 'formscrm_crmname_child_plugin_notice' );

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

/**
 * Child plugin notice
 *
 * @return void
 */
function formscrm_crmname_child_plugin_notice() {
	echo '<div class="error"><p>';
	esc_html_e( 'FORMSCRM crmname: We need the parent plugin FormsCRM to be installed and activated.', 'formscrm-crmname.' );
	echo sprintf(
		__( 'You can find it in <a href="%s">Official repository</a>.', 'your__text_domain' ),
		esc_url( admin_url() ) . 'plugin-install.php?s=formscrm&tab=search&type=term'
	);

	echo '</p></div>';
}
