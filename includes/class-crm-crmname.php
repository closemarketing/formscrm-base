<?php
/**
 * CRMNAME connect library
 *
 * Has functions to login, list fields and create leadº
 *
 * @author   closemarketing
 * @category Functions
 * @package  Gravityforms CRM
 * @version  1.0.0
 */

/**
 * Class for Holded connection.
 */
class CRMLIB_CRMNAME {
	/**
	 * Gets information from Holded CRM
	 *
	 * @param string $url URL for module.
	 * @return array
	 */
	private function api( $method, $url, $apikey ) {
		$args     = array(
			'method'  => $method, // 'GET', 'POST', 'PUT', 'DELETE
			'headers' => array(
				'Authorization' => 'Token ' . $apikey,
			),
			'timeout' => 120,
		);
		$url      = 'https://api.domain.com/' . $url;
		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			formscrm_error_admin_message( 'ERROR', $response->errors['http_request_failed'][0] );
			return false;
		} else {
			$body = wp_remote_retrieve_body( $response );

			return json_decode( $body, true );
		}
	}

	/**
	 * Logins to a CRM
	 *
	 * @param  array $settings settings from Gravity Forms options.
	 * @return false or id     returns false if cannot login and string if gets token
	 */
	public function login( $settings ) {
		$apikey     = isset( $settings['fc_crm_apipassword'] ) ? $settings['fc_crm_apipassword'] : '';
		$get_result = $this->api( 'GET', 'settings/my-account/', $apikey );

		if ( $apikey && isset( $get_result['count'] ) && $get_result['count'] > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * List modules of a CRM
	 *
	 * @param  array $settings settings from Gravity Forms options.
	 * @return array           returns an array of mudules
	 */
	public function list_modules( $settings ) {
		$modules = array(
			array(
				'name'  => 'contacts',
				'value' => 'contacts',
				'label' => __( 'Contacts', 'formscrm-crmname' ),
			),
			array(
				'name'  => 'companies',
				'value' => 'companies',
				'label' => __( 'Companies', 'formscrm-crmname' ),
			),
		);
		return $modules;
	}

	/**
	 * List fields for given module of a CRM
	 *
	 * @param  array $settings settings from Gravity Forms options.
	 * @return array           returns an array of mudules
	 */
	public function list_fields( $settings ) {
		$apikey = isset( $settings['fc_crm_apipassword'] ) ? $settings['fc_crm_apipassword'] : '';
		$module = formscrm_get_module( 'contacts' );

		formscrm_debug_message( __( 'Module active:', 'formscrm-crmname' ) . $module );
		$fields = array();
		if ( 'contacts' === $module ) {
			$fields[] = array(
				'name'     => 'owner',
				'label'    => __( 'username of the owner of the contact', 'formscrm-crmname' ),
				'required' => false,
			);
		}
		return $fields;
	}

	/**
	 * Creates an entry for given module of a CRM
	 *
	 * @param  array $settings settings from Gravity Forms options.
	 * @param  array $merge_vars array of values for the entry.
	 * @return array           id or false
	 */
	public function create_entry( $settings, $merge_vars ) {
		$apikey  = isset( $settings['fc_crm_apipassword'] ) ? $settings['fc_crm_apipassword'] : '';
		$module  = formscrm_get_module( 'contacts' );
		$contact = array();

		foreach ( $merge_vars as $element ) {
			if ( strpos( $element['name'], '|' ) && 0 === strpos( $element['name'], 'custom_fields' ) ) {
				$custom_field = explode( '|', $element['name'] );
				$contact[ $custom_field[0] ][ $custom_field[1] ] = $element['value'];
			} else {
				$contact[ $element['name'] ] = $element['value'];
			}
		}
		$result = $this->api( 'POST', $module, $contact, $apikey );

		if ( isset( $result['id'] ) ) {
			return $result['id'];
		} else {
			$message = isset( $result['detail'] ) ? $result['detail'] : '';
			formscrm_debug_email_lead( 'CRMNAME', 'Error ' . $message, $merge_vars );
		}
	}

} //from Class
