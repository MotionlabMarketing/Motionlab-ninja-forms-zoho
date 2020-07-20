<?php

if ( !defined( 'ABSPATH' ) )
    exit;

/* --- NINJA FORMS ZOHO CRM INTEGRATION --- */
/**
 * Retrieve db options as global variables to minimize db calls
 * 
 * @global array $nfzohocrm_site_options
 * @global array $nfzohocrm_comm_data
 */
function nfzohocrm_load_globals() {

    global $nfzohocrm_site_options;
    global $nfzohocrm_comm_data;


    // array of keyed settings in ninja_forms_settings option array
    $keys_to_extract = array(
        'nfzohocrm_authtoken', // Zoho auth token
    );

    /*
     * the most recent communication messages in a separate variable array 
     * so it doesn't get overwritten when updating the options
     */
    $nfzohocrm_comm_data = get_option( 'nfzohocrm_comm_data' );

    /*
     * If a Post-NF3 mode isn't defined, grab the array of legacy options stored
     * in the wp-options database 
     */
    $legacy_settings = get_option( 'nfzohocrm_settings' );

    if ( '2.9x' == NFZOHOCRM_MODE ) {

        $temp_array = $legacy_settings;
    } else {

        // In a NF 3.0 setup, the settings are all stored in option ninja_forms_settings
        $nf_settings_array = get_option( ' ninja_forms_settings' );

        foreach ( $keys_to_extract as $key ) {

            if ( isset( $nf_settings_array[ $key ] ) ) {

                // use the NF3 version if already set
                $temp_array[ $key ] = $nf_settings_array[ $key ];
            } elseif ( isset( $legacy_settings[ $key ] ) ) {

                // If NF3 key isn't set, grab the NF2.9 version
                $temp_array[ $key ] = $legacy_settings[ $key ];
            } else {

                // ensure it is at least set
                $temp_array[ $key ] = '';
            }
        }
    }

    //set the global
    $nfzohocrm_site_options = $temp_array;
}
/**
 * Extract the stored advanced codes for Zoho CRM
 * 
 * @return array
 */
function nfzohocrm_extract_advanced_codes() {

    $settings_key = 'nfzohocrm_advanced_codes';

    $advanced_codes_array = array(); //initialize
    $nf_settings_array = get_option( ' ninja_forms_settings' );

    if ( isset( $nf_settings_array[ $settings_key ] ) ) {

        $advanced_codes_setting = $nf_settings_array[ $settings_key ];

        $advanced_codes_array = array_map( 'trim', explode( ',', $advanced_codes_setting ) );
    }

    return $advanced_codes_array;
}

/**
 * Sets the data_dump option for quick debug
 * 
 * @global array $nfzohocrm_comm_data
 * @param type $data
 */
function nfzohocrm_data_dump( $data ) {

    global $nfzohocrm_comm_data;

    $nfzohocrm_comm_data[ 'data_dump' ] = $data;

    update_option( 'nfzohocrm_comm_data', $nfzohocrm_comm_data );
}

