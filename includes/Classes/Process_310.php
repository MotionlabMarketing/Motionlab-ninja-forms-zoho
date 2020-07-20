<?php

/**
 * Handles the Process method of the Action using 3.1.0 and earlier classes
 *
 * Given the action_settings, data, and form id
 * @author stuartlb3
 */
class ZohoCRM_Process310 {

    /**
     * Form Action Settings
     * @var array
     */
    protected $action_settings;

    /**
     * Form ID of fired action
     * @var string
     */
    protected $form_id;

    /**
     * Data of fired action
     * @var array
     */
    protected $data;

    public function __construct( $action_settings, $form_id, $data ) {

        $this->action_settings = $action_settings;

        $this->form_id = $form_id;

        $this->data = $data;
    }

    public function processAction() {

            $auth_params = NF_ZohoCRM()->factory->authParams();

            $is_authorized = $auth_params->isAuthorized();

            if ( !$is_authorized ) {

                NF_ZohoCRM()->modify_nfzohocrm_comm_data( $auth_params->status() );
                NF_ZohoCRM()->update_nfzohocrm_comm_data();
                return;
            } else {

                $authtoken = $auth_params->credentials( 'authtoken' );
            }

            // isolate the action_data for the submission
            $field_map_data = $this->action_settings[ NF_ZohoCRM()->constants->field_map_action_key ];

            $request_object = new ZohoRequestObject( $field_map_data );

            $request_array = $request_object->get_request_array();

            NF_ZohoCRM()->stored_data->modifyCommData( $field_map_data, 'field_map_array' );

            NF_ZohoCRM()->stored_data->modifyCommData( $request_array, 'request_array' );

            if ( class_exists( 'ZohoCommObjectPlus' ) ) {

                $zohocomm = new ZohoCommObjectPlus( $authtoken );
            } else {

                $zohocomm = new ZohoCommObject( $authtoken );
            }

            foreach ( $request_array as $field_iterator ) {

                $zohocomm->add_field_to_request( $field_iterator[ 'form_field' ], $field_iterator );
            }


            $zohocomm->process_form_request();

            $response = $zohocomm->get_processed_response();
            NF_ZohoCRM()->stored_data->modifyCommData( $response, 'response' );

            $comm_status = $zohocomm->get_status_update();

            NF_ZohoCRM()->stored_data->modifyCommData( $comm_status, 'zoho_comm_status' );

            $errors = $zohocomm->get_error_array();

            NF_ZohoCRM()->stored_data->modifyCommData( $errors, 'errors' );

            NF_ZohoCRM()->stored_data->updateCommData();


            /**
             * Array of data for each newly created module
             *
             * Includes Message, Id, Created Time, Created By
             */
            $new_record_ids = $zohocomm->get_new_record_id_array();

            /*
             * Make the new IDs available for other uses
             */
            do_action( 'nfzohocrm_process_new_record_ids', $new_record_ids );

            return $this->data;
        
    }

    /**
     * Returns data of fired action
     *
     * May have been altered during processing
     * @return array
     */
    public function getData() {

        return $this->data;
    }

}
