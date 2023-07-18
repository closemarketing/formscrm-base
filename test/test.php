<?php
/** 
 * API DOCS: URL of API
 */
//define('WP_DEBUG',true);
include_once 'debugtest.php';


define( 'WP_USE_THEMES', false ); // Don't load theme support functionality
require '../../../../wp-load.php';

$credentials = file_get_contents( 'credentials.json' );
$settings    = json_decode( $credentials, true );


include '../includes/class-crm-crmname.php';

/**
 * ## Testing CRMNAME
 * --------------------------- */

$crm_holded = new CRMLIB_CRMNAME();

echo '<p>Login CRMNAME CRM:</p>';
$login_api = $crm_holded->login($settings);

echo '<pre>';
print_r($login_api);
echo '</pre>';

echo '<p>List Fields</p>';
$list_fields = $crm_holded->list_fields($settings);
echo '<pre>';
print_r($list_fields);
echo '<pre>';

echo '<p>List Modules</p>';
$list_modules = $crm_holded->list_modules($settings);
echo '<pre>';
print_r($list_modules);
echo '<pre>';

echo '<p>Create lead from test mergevar</p>';

$test_mergevars = array(
	array( 'name' => 'name', 'value' => 'User test'),
	array( 'name' => 'tradename', 'value' => 'User test'),
	array( 'name' => 'code', 'value' => 'B1999999'),
	array( 'name' => 'email', 'value' => 'prueba@prueba.com'),
	array( 'name' => 'phone', 'value' => '823322323'),
	array( 'name' => 'mobile', 'value' => '23212323'),
	array( 'name' => 'address', 'value' => 'Calle Turin'),
);
$leadid = $crm_holded->create_entry( $settings, $test_mergevars);
echo '<pre>';
print_r($leadid);
echo '<pre>';
