<?php
/**
 * Plugin Name: FormsCRM #ProperName# Addon
 * Plugin URI:  https://close.marketing/plugins/formscrm-#name#/
 * Description: Adds customize to #ProperName#.
 * Version:     1.0
 * Author:      Closemarketing
 * Author URI:  https://close.marketing/
 * Text Domain: formscrm-#name#
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
 * Prefix:      formscrm_#name#
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

add_action( 'plugins_loaded', 'formscrm_#name#_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function formscrm_#name#_plugin_init() {
	load_plugin_textdomain( 'formscrm-#name#', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_filter(
	'formscrm_choices',
	function( $choices ) {
		// Add a new option to the styles array.
		$choices[] = array(
			'label' => '#ProperName#',
			'value' => '#name#',
		);

		// Return the array of style options.
		return $choices;
	}
);


add_filter(
	'formscrm_crmlib_path',
	function( $choices ) {
		// Add a new option to the styles array.

		$choices['#name#'] = plugin_dir_path( __FILE__ ) . 'includes/class-crm-#name#.php';

		// Return the array of paths.
		return $choices;
	}
);

add_filter(
	'formscrm_dependency_apipassword',
	function( $choices ) {
		// Add a new option to the styles array.

		$choices[] = '#name#';

		// Return the array of paths.
		return $choices;
	}
);

add_action( 'admin_init', 'formscrm_#name#_dependency' );
/**
 * Checks depency
 *
 * @return void
 */
function formscrm_#name#_dependency() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'formscrm/formscrm.php' ) ) {
		add_action( 'admin_notices', 'formscrm_#name#_child_plugin_notice' );

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
function formscrm_#name#_child_plugin_notice() {
	echo '<div class="error"><p>';
	esc_html_e( 'FORMSCRM #name#: We need the parent plugin FormsCRM to be installed and activated.', 'formscrm-#name#.' );
	echo sprintf(
		__( 'You can find it in <a href="%s">Official repository</a>.', 'your__text_domain' ),
		esc_url( admin_url() ) . 'plugin-install.php?s=formscrm&tab=search&type=term'
	);

	echo '</p></div>';
}
