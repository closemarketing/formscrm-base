<?php
/**
 * Plugin Name: FormsCRM Clientify Addon
 * Plugin URI:  https://close.marketing/plugins/formscrm-clientify/
 * Description: Adds customize to Clientify.
 * Version:     1.0
 * Author:      Closemarketing
 * Author URI:  https://close.marketing/
 * Text Domain: formscrm-clientify
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
 * Prefix:      formscrm_clientify
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

add_action( 'plugins_loaded', 'formscrm_clientify_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function formscrm_clientify_plugin_init() {
	load_plugin_textdomain( 'formscrm-clientify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_filter(
	'formscrm_choices',
	function( $choices ) {
		// Add a new option to the styles array.
		$choices[] = array(
			'label' => 'Clientify',
			'value' => 'clientify',
		);

		// Return the array of style options.
		return $choices;
	}
);


add_filter(
	'formscrm_crmlib_path',
	function( $choices ) {
		// Add a new option to the styles array.

		$choices['clientify'] = plugin_dir_path( __FILE__ ) . 'includes/class-crm-clientify.php';

		// Return the array of paths.
		return $choices;
	}
);

add_filter(
	'formscrm_dependency_apipassword',
	function( $choices ) {
		// Add a new option to the styles array.

		$choices[] = 'clientify';

		// Return the array of paths.
		return $choices;
	}
);

add_action( 'admin_init', 'formscrm_clientify_dependency' );
/**
 * Checks depency
 *
 * @return void
 */
function formscrm_clientify_dependency() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'formscrm/formscrm.php' ) ) {
		add_action( 'admin_notices', 'formscrm_clientify_child_plugin_notice' );

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
function formscrm_clientify_child_plugin_notice() {
	echo '<div class="error"><p>';
	esc_html_e( 'FORMSCRM clientify: We need the parent plugin FormsCRM to be installed and activated.', 'formscrm-clientify.' );
	echo sprintf(
		__( 'You can find it in <a href="%s">Official repository</a>.', 'your__text_domain' ),
		esc_url( admin_url() ) . 'plugin-install.php?s=formscrm&tab=search&type=term'
	);

	echo '</p></div>';
}

if ( ! function_exists( 'formscrm_clientify_fs' ) ) {
	// Create a helper function for easy SDK access.
	function formscrm_clientify_fs() {
		global $formscrm_clientify_fs;

		if ( ! isset( $formscrm_clientify_fs ) ) {
			// Include Freemius SDK.
			if ( file_exists( dirname( dirname( __FILE__ ) ) . '/formscrm/vendor/freemius/wordpress-sdk/start.php' ) ) {
				// Try to load SDK from parent plugin folder.
				require_once dirname( dirname( __FILE__ ) ) . '/formscrm/vendor/freemius/wordpress-sdk/start.php';
			} elseif ( file_exists( dirname( dirname( __FILE__ ) ) . '/formscrm-premium/vendor/freemius/wordpress-sdk/start.php' ) ) {
				// Try to load SDK from premium parent plugin folder.
				require_once dirname( dirname( __FILE__ ) ) . '/formscrm-premium/vendor/freemius/wordpress-sdk/start.php';
			} else {
				require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';
			}

			$formscrm_clientify_fs = fs_dynamic_init(
				array(
					'id'               => '9345',
					'slug'             => 'formscrm-clientify',
					'premium_slug'     => 'formscrm-clientify',
					'type'             => 'plugin',
					'public_key'       => 'pk_47062ca8e9f87f0c39bfd6a304bfd',
					'is_premium'       => true,
					'is_premium_only'  => true,
					'has_paid_plans'   => true,
					'is_org_compliant' => false,
					'trial'            => array(
						'days'               => 7,
						'is_require_payment' => true,
					),
					'parent'           => array(
						'id'         => '8504',
						'slug'       => 'formscrm',
						'public_key' => 'pk_fa93ef3eb788d04ac4803d15c1511',
						'name'       => 'FormsCRM',
					),
					'menu'             => array(
						'first-path' => 'admin.php?page = formscrm',
						'support'    => false,
					),
				)
			);
		}

		return $formscrm_clientify_fs;
	}
}
