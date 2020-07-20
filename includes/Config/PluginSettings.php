<?php

if ( !defined( 'ABSPATH' ) )
    exit;


return apply_filters( 'nfzohorm_plugin_settings', array(
    /*
      |--------------------------------------------------------------------------
      | Zoho Auth Token
      |--------------------------------------------------------------------------
     */

    NF_ZohoCRM()->constants->auth_token => array(
        'id' => NF_ZohoCRM()->constants->auth_token,
        'type' => 'textbox',
        'label' => __( 'Zoho CRM Auth Token', 'ninja-forms-zoho-crm' ),
    ),
    NF_ZohoCRM()->constants->client_id => array(
        'id' => NF_ZohoCRM()->constants->client_id,
        'type' => 'textbox',
        'label' => __( 'Client ID', 'ninja-forms-zoho-crm' ),
    ),
    NF_ZohoCRM()->constants->client_secret => array(
        'id' => NF_ZohoCRM()->constants->client_secret,
        'type' => 'textbox',
        'label' => __( 'Client Secret', 'ninja-forms-zoho-crm' ),
    ),
    NF_ZohoCRM()->constants->authorization_code => array(
        'id' => NF_ZohoCRM()->constants->authorization_code,
        'type' => 'textbox',
        'label' => __( 'Authorization Code', 'ninja-forms-zoho-crm' ),
    ),
    NF_ZohoCRM()->constants->refresh_token => array(
        'id' => NF_ZohoCRM()->constants->refresh_token,
        'type' => 'html',
        'label' => __( 'Refresh Token', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    /*
      |--------------------------------------------------------------------------
      | Communication Status
      |--------------------------------------------------------------------------
     */
    'nfzohocrm_authtoken_connection_test_link' => array(
        'id' => 'nfzohocrm_authtoken_connection_test_link',
        'type' => 'html',
        'label' => __( 'Test your API authorization', 'ninja-forms-zoho-crm' ),
        'html' => '', // created on construction
    ),
    NF_ZohoCRM()->constants->comm_status => array(
        'id' => NF_ZohoCRM()->constants->comm_status,
        'type' => 'html',
        'label' => __( 'Communication Status', 'ninja-forms-zoho-crm' ),
        'html' => '', // built in Settings class
    ),
    NF_ZohoCRM()->constants->errors => array(
        'id' => NF_ZohoCRM()->constants->errors,
        'type' => 'html',
        'label' => __( 'Errors', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    /*
      |--------------------------------------------------------------------------
      | Advanced Codes
      |--------------------------------------------------------------------------
     */
    NF_ZohoCRM()->constants->advanced_commands_key => array(
        'id' => NF_ZohoCRM()->constants->advanced_commands_key,
        'type' => 'textbox',
        'label' => __( 'Advanced Commands', 'ninja-forms-zoho-crm' ),
    ),
    /*
      |--------------------------------------------------------------------------
      | Support Messages
      |--------------------------------------------------------------------------
     */
    /*
      'support_messages' => array(
      'id' => 'support_messages',
      'type' => 'html',
      'label' => __( 'Support Messages', 'ninja-forms-zoho-crm' ),
      'html' => '',
      ),
     *
     */
    NF_ZohoCRM()->constants->field_map_array => array(
        'id' => NF_ZohoCRM()->constants->field_map_array,
        'type' => 'html',
        'label' => __( 'Field Map Array', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    NF_ZohoCRM()->constants->structured_request_array => array(
        'id' => NF_ZohoCRM()->constants->structured_request_array,
        'type' => 'html',
        'label' => __( 'Structured Request', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    NF_ZohoCRM()->constants->structured_response => array(
        'id' => NF_ZohoCRM()->constants->structured_response,
        'type' => 'html',
        'label' => __( 'Structured Response', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    NF_ZohoCRM()->constants->raw_response => array(
        'id' => NF_ZohoCRM()->constants->raw_response,
        'type' => 'html',
        'label' => __( 'Raw Response', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    /*
      |--------------------------------------------------------------------------
      | Account Information
      |--------------------------------------------------------------------------
     */
        NF_ZohoCRM()->constants->retrieved_field_names => array(
        'id' => NF_ZohoCRM()->constants->retrieved_field_names,
        'type' => 'html',
        'label' => __( 'Available Field Names', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
    NF_ZohoCRM()->constants->manual_field_map => array(
        'id' => NF_ZohoCRM()->constants->manual_field_map,
        'type' => 'html',
        'label' => __( 'Field Mapping Lookup', 'ninja-forms-zoho-crm' ),
        'html' => '',
    ),
        ) );
