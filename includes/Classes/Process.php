<?php

/**
 * Handles the Process method of the Action
 *
 * Given the action_settings, data, and form id
 * @author stuartlb3
 */
class ZohoCRM_Process {

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

    /**
     * Authorization parameters object
     * @var ZohoCRM_AuthParams
     */
    protected $auth_params;

    /**
     * Request array constructed from action settings
     * @var array
     */
    protected $request_array;

    /**
     * Communication object through which API requests are made
     *
     * @var ZohoCRM_Comm
     */
    protected $comm_object;

    /**
     * Request Manager object
     * @var ZohoCRM_RequestManager
     * @see ZohoCRM_RequestManager
     */
    protected $request_manager;

    public function __construct( $action_settings, $form_id, $data ) {

        $this->action_settings = $action_settings;

        $this->form_id = $form_id;

        $this->data = $data;
    }

    public function processAction() {

        NF_ZohoCRM()->stored_data->resetCommData();
              
        $this->auth_params = NF_ZohoCRM()->factory->authParams();

        NF_ZohoCRM()->stored_data->modifyCommData( $this->auth_params->status(), 'comm_status' );
        
        if ( $this->auth_params->isAuthorized() ) {

            // set the factory authlevel, factor in deciding downstream objects
            NF_ZohoCRM()->factory->authlevel = $this->auth_params->authLevel();

            $this->buildRequestArray();

            if ( NF_ZohoCRM()->constants->authlevel_v1 === $this->auth_params->authLevel() ) {

                $this->v1Communication();
            } elseif ( NF_ZohoCRM()->constants->authlevel_v2 === $this->auth_params->authLevel() ) {

                $this->v2Communication();
            }
        }

        NF_ZohoCRM()->stored_data->updateCommData();
    }

    protected function v2Communication() {
        
        NF_ZohoCRM()->stored_data->modifyCommData('320', 'settings_version', FALSE);
  
        $this->comm_object = NF_ZohoCRM()->factory->commObject( $this->auth_params->credentials() );

        $this->request_manager = NF_ZohoCRM()->factory->requestManager( $this->request_array, $this->comm_object );

        $this->request_manager->iterateRequests();

        $raw_response = $this->request_manager->getRawResponse();

        $structured_response = $this->request_manager->getStructuredResponse();

        NF_ZohoCRM()->stored_data->modifyCommData( $structured_response, 'structured_response' );
    }

    /**
     * Uses API v1 to make requests
     *
     * Uses local instantiation because v1Communication will be deprecated and
     * sharing will add complexity in ensuring common public methods
     */
    protected function v1Communication() {

        NF_ZohoCRM()->stored_data->modifyCommData('310', 'settings_version', FALSE);
  
        $zohocomm = NF_ZohoCRM()->factory->commObject( $this->auth_params->credentials() );

        foreach ( $this->request_array as $field_iterator ) {

            $zohocomm->add_field_to_request( $field_iterator[ 'form_field' ], $field_iterator );
        }

        $zohocomm->iterateRequestArray( $this->request_array );

        $zohocomm->process_form_request();

        $response = $zohocomm->get_processed_response();

        NF_ZohoCRM()->stored_data->modifyCommData( $response, 'raw_response' );

        $comm_status = $zohocomm->get_status_update();

        NF_ZohoCRM()->stored_data->modifyCommData( $comm_status, 'comm_status' );

        $errors = $zohocomm->get_error_array();

        NF_ZohoCRM()->stored_data->modifyCommData( $errors, 'errors' );

        /*
         * Array of data for each newly created module
         *
         * Includes Message, Id, Created Time, Created By
         */
        $new_record_ids = $zohocomm->get_new_record_id_array();

        // Make the new IDs available for other uses
        do_action( 'nfzohocrm_process_new_record_ids', $new_record_ids );
    }

    /**
     * Builds request array from action settings field map action key
     * 
     * Field map array construction is byproduct of request array, so retrieve
     * and store it for technical support
     */
    protected function buildRequestArray() {

        // isolate the action_data for the submission
        $field_map_data = $this->action_settings[ NF_ZohoCRM()->constants->field_map_action_key ];

        $auth_level = $this->auth_params->authLevel();

        $request_array_object = NF_ZohoCRM()->factory->buildRequestArray( $field_map_data, $auth_level );

        $field_map_array = $request_array_object->getFieldMapArray();

        NF_ZohoCRM()->stored_data->modifyCommData( $field_map_array, 'field_map_array', FALSE );

        $this->request_array = $request_array_object->getRequestArray();

        NF_ZohoCRM()->stored_data->modifyCommData( $this->request_array, 'request_array', FALSE );
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
