<?php

class ZohoCRM_Listener {

    /**
     * Listens for GET requests with specific commands
     *
     * Calls specific functions based on the request made; uses
     * a switch/case so that only vetted functions are called
     */
    public static function listener() {

        $listener_url = 'nfzohocrm_instructions';

        $trigger = filter_input(INPUT_GET,$listener_url);

        switch ( $trigger ) {

            case 'test-connection':
                self::apiConnectionTest();
                break;

            default:
                break;
        }
    }

    /**
     * Make sample request through API to test connection
     * 
     * Force use of auth level 2; if it fails, help user debug issues
     * 
     */
    public static function apiConnectionTest() {

        NF_ZohoCRM()->stored_data->resetCommData();
        
        $status =  'Performing Connection Test';

        NF_ZohoCRM()->stored_data->modifyCommData( $status, 'comm_status', TRUE );
        
        NF_ZohoCRM()->stored_data->modifyCommData('320', 'settings_version', FALSE);
        
        $auth_params = NF_ZohoCRM()->factory->authParams();

        $auth_level = $auth_params->authLevel();

        NF_ZohoCRM()->factory->authlevel = NF_ZohoCRM()->constants->authlevel_v2;

        $field_map_data = NF_ZohoCRM::config( 'ConnectionTester_v2' );

        $request_array_object = NF_ZohoCRM()->factory->buildRequestArray( $field_map_data, $auth_level );

        $request_array = $request_array_object->getRequestArray();

        $comm_object = NF_ZohoCRM()->factory->commObject( $auth_params->credentials() );

        $request_manager = NF_ZohoCRM()->factory->requestManager( $request_array, $comm_object );

        $request_manager->iterateRequests();

        $structured_response = $request_manager->getStructuredResponse();

        NF_ZohoCRM()->stored_data->modifyCommData( $structured_response, 'structured_response' );
        
        NF_ZohoCRM()->stored_data->updateCommData();
        
        $redirect = admin_url() . 'admin.php?page=nf-settings#zoho-settings';

        wp_redirect( $redirect );
        exit;
    }

}

add_action( 'init', 'ZohoCRM_Listener::listener' );
