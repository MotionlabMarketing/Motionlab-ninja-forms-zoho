<?php

/* --- NINJA FORMS ZOHO CRM INTEGRATION --- */


/* ----------
  PROCESS DATA
  - build xml file from form
  - validate form data if necessary
  - log or display results from API
  ------------------------------------------------------------------------------------------------------------ */



/* ----------
  Process Insert Form Data as Lead
  ----- */

function nfzohocrm_process_form_to_insert_form_data() {

    global $ninja_forms_processing;
    global $nfzohocrm_site_options;

    if ( isset( $nfzohocrm_site_options[ 'nfzohocrm_authtoken' ] ) ) {

        $authtoken = $nfzohocrm_site_options[ 'nfzohocrm_authtoken' ];
    } else {

        $authtoken = '';
    }


    /* --- Check if initiating form should be inserted as Zoho lead --- */

    $the_droids = $ninja_forms_processing->get_form_setting( 'nfzohocrm-add-to-leads' );
    if ( !$the_droids ) {
        return;
    } ///this is not the form you're looking for

    /* --- process form --- */

    $field_array = $ninja_forms_processing->get_all_fields();

    //create the communication object

    if ( class_exists( 'ZohoCommObjectPlus' ) ) {

        $zohocomm = new ZohoCommObjectPlus( $authtoken );
    } else {

        $zohocomm = new ZohoCommObjectDeprecated( $authtoken );
    }

    foreach ( $field_array as $field_id => $user_value ) { //cycle through each submitted field
        $field = ninja_forms_get_field_by_id( $field_id );

        /* --- Determine which field to map to --- */

        $map_args[ 'field_map' ] = 'none'; //set fail-safe mapped field
        
        if ( isset( $field[ 'type' ] ) ) {
            
            $map_args[ 'field_type' ] = $field[ 'type' ];
        } else {
            
            $map_args[ 'field_type' ] = '';
        }

        
        if ( isset( $field[ 'data' ][ 'nfzohocrm_field_map' ] ) ) {

            $mapped_field_temp = $field[ 'data' ][ 'nfzohocrm_field_map' ];

            $mapped_field_explode = explode( '.', $mapped_field_temp );

            if ( 1 == count( $mapped_field_explode ) ) {
                // no array means it is a lead map, so add it to the lead array
//                $map_args = array( 'field_map' => $mapped_field_explode[ 0 ], 'module' => 'Leads' );
                $map_args['field_map'] = $mapped_field_explode[ 0 ];
                $map_args['module'] = 'Leads' ;
                
            } else {

//                $map_args = array( 'field_map' => $mapped_field_explode[ 1 ], 'module' => $mapped_field_explode[ 0 ] );
                $map_args['field_map'] = $mapped_field_explode[ 1 ];
                $map_args['module'] = $mapped_field_explode[ 0 ] ;
            }
        }

        // Field Map Adjustments
        switch ($map_args[ 'field_map' ]) {

            case 'divider':

                $map_args[ 'field_map' ] = 'none'; //set fail-safe mapped field
                break;

            case 'custom':

                if ( isset( $field[ 'data' ][ 'nfzohocrm_custom_field_map' ] ) ) {

//                    $map_args[ 'field_map' ] = preg_replace( '/[^ \w-]+/', '', $field[ 'data' ][ 'nfzohocrm_custom_field_map' ] );
                  $map_args[ 'field_map' ] = esc_html($field[ 'data' ][ 'nfzohocrm_custom_field_map' ]);
                    
                }
                break;

            case 'Annual Revenue':

                $user_value = intval( preg_replace( '/[^0-9.]*/', "", $user_value ) );
                break;
        }

        // Ensure that field map and user value have valid values, otherwise move on to next field
        if ( 'none' === $map_args[ 'field_map' ] || '' === $user_value ) {

            continue;
        }

        // Field Type Adjustments
        switch ($map_args[ 'field_type' ]) {

            case '_checkbox':

                if ( 'checked' == $user_value ) {

                    $user_value = 'true';
                } else {

                    $user_value = 'false';
                }
                break;
        }



        if ( is_array( $user_value ) ) { //multiple select is being used so convert it to a comma-delineated string
            $user_value = esc_attr( implode( ",", $user_value ) );
        }


        $zohocomm->add_field_to_request( $user_value, $map_args );
    }
    //end foreach field

    $zohocomm->process_form_request();

    $response = $zohocomm->get_processed_response();
    $errors = $zohocomm->get_error_array();
    $combined_response = array();

    if ( $response ) {
        $combined_response = array_merge( $combined_response, array( 'response' => $response ) );
    }
    if ( $errors ) {
        $combined_response = array_merge( $combined_response, array( 'errors' => $errors ) );
    }

    $status_array[ 'raw_response' ] = $combined_response;

    $status_array[ 'status_update' ] = $zohocomm->get_status_update();
    $status_array[ 'raw_request' ] = $zohocomm->get_raw_request_array();


    nfzohocrm_update_comm_status( $status_array );
    nfzohocrm_write_zoho_results_to_log( $status_array );
}

// end nfzohocrm_process_form_to_insert_form_data


function nfzohocrm_update_comm_status( $status_array ) {

    global $nfzohocrm_site_options;
    global $nfzohocrm_comm_data;

    if ( isset( $status_array[ 'status_update' ] ) ) {
        $nfzohocrm_site_options[ 'zoho_comm_status' ] = $status_array[ 'status_update' ];
    }

    $nfzohocrm_comm_data[ 'nfzohocrm_most_recent_raw_request' ] = serialize( $status_array[ 'raw_request' ] );
    $nfzohocrm_comm_data[ 'nfzohocrm_most_recent_raw_response' ] = serialize( $status_array[ 'raw_response' ] );


    update_option( 'nfzohocrm_settings', $nfzohocrm_site_options );
    update_option( 'nfzohocrm_comm_data', $nfzohocrm_comm_data );
}

// end nfzohocrm_update_comm_status

function nfzohocrm_write_zoho_results_to_log( $status_array ) {

    if ( apply_filters( 'nfzohocrm_write_to_error_log', false ) ) {

        $fp = fopen( NFZOHOCRM_PLUGIN_DIR . 'zoho-response-log.txt', 'a' );

        fwrite( $fp, "\n" . "OUTCOME: \t" . serialize( $status_array ) . "\n" );

        fclose( $fp );
    }
}

// end nfzohocrm_write_zoho_results_to_log