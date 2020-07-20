<?php

/* --- NINJA FORMS ZOHO CRM INTEGRATION --- */


/* ----------
  EXCHANGE DATA WITH ZOHO CRM THROUGH THE API
  ------------------------------------------------------------------------------------------------------------ */

/* ----------
  Insert Record
  - given the desired module and xml data, passes data to zoho and returns success or error result
  ----- */

class ZohoCommObject {

    protected $authtoken;
    protected $base_url;
    protected $scope;
    protected $wftrigger;
    protected $isapproval;
    protected $raw_request_array;
    protected $validated_request;
    protected $error_array;
    protected $new_record_id_array;
    protected $raw_response;
    protected $processed_response;
    protected $status_update;

    function __construct( $authtoken = '' ) {

        /*
         * Standard init timing doesn't apply filters in comm object
         * Re-apply filters so that they are available when called
         */
//        nfzohocrm_advanced_command_filters();
        NF_ZohoCRM()->advanced_commands->applyAdvancedCommandFilters();
        
        $this->build_base_url();

        $this->authtoken = $authtoken;
        $this->scope = 'crmapi';
        $this->wftrigger = 'false';
        $this->isapproval = 'false';

        $this->raw_request_array = array();

        $this->raw_response = array();  //initialize empty value
        $this->error_array = array();
        $this->processed_response = array();
        $this->status_update = '';  //initialize empty value
    }

    /*
      PUBLIC METHODS
      -------------------- */

    public function iterateRequestArray( $request_array){
        
        foreach($request_array as $module =>$module_request_array){
            
            foreach($module_request_array as $key=>$value){
                
                $this->add_field_to_request($value, array('field_map'=>$key, 'module'=>$module));
            }
        }
    }
    
    /**
     *
     * @param string $user_value
     *
     * @param array $map_args
     * 		'field_map' => string
     * 		'module' => string
     *
     */
    public function add_field_to_request( $user_value, $map_args = array( 'field_map' => 'none', 'module' => 'Leads' ) ) {

        if ( 'none' === $map_args[ 'field_map' ] ) {
            return false;
        }

        $this->raw_request_array[ $map_args[ 'module' ] ][ $map_args[ 'field_map' ] ] = esc_attr($user_value);

        return true;
    }

    public function process_form_request() {

        if ( empty( $this->authtoken ) ) {

            $this->error_array[] = __( 'The authorization token has not been set.', 'ninja-forms-zoho-crm' );
            return false;
        }

        $this->set_parameters();

        // process each module in order
        $module_array = array( 'Leads', 'Accounts', 'Contacts', 'Potentials', 'Tasks', 'Notes' );

        foreach ( $module_array as $value ) {

            if ( isset( $this->raw_request_array[ $value ] ) ) {

                $this->process_module( $value );
            }
        }
    }

    /*
      INTERNAL PROCESSING
      -------------------- */

    /**
     * Create URL endpoint for API
     * 
     * Uses filter with optional advanced command to change endpoint; some
     * EU customers required EU specific endpoint
     * 
     */
    protected function build_base_url(){
        
        $endpoint = apply_filters('nfzohocrm_alt_endpoint','crm.zoho.com');
        
        $this->base_url = 'https://'.$endpoint.'/crm/private/json';
    }
    
    protected function set_parameters() {

        if ( isset( $this->raw_request_array[ 'Parameters' ] [ 'wfTrigger' ] ) ) {

            if ( $this->raw_request_array[ 'Parameters' ] [ 'wfTrigger' ] ) {

                $this->wftrigger = 'true';
            }
        }

        if ( isset( $this->raw_request_array[ 'Parameters' ] [ 'isApproval' ] ) ) {

            if ( $this->raw_request_array[ 'Parameters' ] [ 'isApproval' ] ) {
                $this->isapproval = 'true';
            }
        }
    }

    protected function process_module( $module ) {

        $this->validate_module_data( $module );

        $this->generate_xml( $this->raw_request_array[ $module ], $module ); // inserts built xml data into array, false if error during build

        if ( !isset( $this->validated_request[ $module ] ) ) {
            return false;
        }

        $this->insert_record( $this->validated_request[ $module ], $module );

        $this->process_response( $module );
    }

    protected function validate_module_data( $module ) {

        switch ( $module ) {
            case 'Leads': // adds last name and company if missing
                if ( !isset( $this->raw_request_array[ 'Leads' ][ 'Last Name' ] ) ) {

                    $this->raw_request_array[ 'Leads' ][ 'Last Name' ] = apply_filters( 'nfzohocrm_default_last_name', 'undisclosed' );
                }

                if ( !isset( $this->raw_request_array[ 'Leads' ][ 'Company' ] ) ) {

                    $this->raw_request_array[ 'Leads' ][ 'Company' ] = apply_filters( 'nfzohocrm_default_company_name', 'undisclosed' );
                }

                break; // Leads

            case 'Accounts':


                break; // Accounts

            case 'Contacts': // adds last name if missing; associate Contact with Account if Account is created

                if ( !isset( $this->raw_request_array[ 'Contacts' ][ 'Last Name' ] ) ) {

                    $this->raw_request_array[ 'Contacts' ][ 'Last Name' ] = apply_filters( 'nfzohocrm_default_last_name', 'undisclosed' );
                }
                if ( isset( $this->raw_request_array[ 'Accounts' ][ 'Account Name' ] ) ) {

                    $this->raw_request_array[ 'Contacts' ][ 'Account Name' ] = $this->raw_request_array[ 'Accounts' ][ 'Account Name' ];
                }
                break; // Contacts

            case 'Potentials':// associate Potential with Account; required by Zoho

                if ( isset( $this->raw_request_array[ 'Accounts' ][ 'Account Name' ] ) ) {
                    $this->raw_request_array[ 'Potentials' ][ 'Account Name' ] = $this->raw_request_array[ 'Accounts' ][ 'Account Name' ];
                }

                if ( isset( $this->raw_request_array[ 'Potentials' ][ 'Closing Date' ] ) ) {

                    $this->raw_request_array[ 'Potentials' ][ 'Closing Date' ] = $this->validateDate( $this->raw_request_array[ 'Potentials' ][ 'Closing Date' ] );
                }

                break;

            case 'Tasks':// associate Lead, Contact, Account with Task; validate due date

                if ( isset( $this->new_record_id_array[ 'Leads' ][ 'Id' ] ) ) {

                    $this->raw_request_array[ 'Tasks' ][ 'LEADID' ] = $this->new_record_id_array[ 'Leads' ][ 'Id' ];
                }

                if ( isset( $this->new_record_id_array[ 'Contacts' ][ 'Id' ] ) ) {

                    $this->raw_request_array[ 'Tasks' ][ 'CONTACTID' ] = $this->new_record_id_array[ 'Contacts' ][ 'Id' ];
                }

                if ( isset( $this->new_record_id_array[ 'Accounts' ][ 'Id' ] ) ) {

                    $this->raw_request_array[ 'Tasks' ][ 'SEID' ] = $this->new_record_id_array[ 'Accounts' ][ 'Id' ];
                    $this->raw_request_array[ 'Tasks' ][ 'SEMODULE' ] = 'Accounts';
                }

                if ( isset( $this->raw_request_array[ 'Tasks' ][ 'Due Date' ] ) ) {

                    $this->raw_request_array[ 'Tasks' ][ 'Due Date' ] = $this->validateDate( $this->raw_request_array[ 'Tasks' ][ 'Due Date' ] );
                }

                break;

            case 'Notes':
                if ( isset( $this->new_record_id_array[ 'Leads' ][ 'Id' ] ) ) {

                    $this->raw_request_array[ 'Notes' ][ 'entityId' ] = $this->new_record_id_array[ 'Leads' ][ 'Id' ];
                } elseif ( isset( $this->new_record_id_array[ 'Contacts' ][ 'Id' ] ) ) {

                    $this->raw_request_array[ 'Notes' ][ 'entityId' ] = $this->new_record_id_array[ 'Contacts' ][ 'Id' ];
                }

                break;
        }
    }

    protected function validateDate( $date, $format = 'm/d/Y' ) { // receives a text field; if it is a date, return it, if it is a date interval, add it to the current timestamp
        $d = DateTime::createFromFormat( $format, $date );

        if ( $d && $d->format( $format ) === $date ) {

            return $date;
        } else {

            $now = new DateTime(); // get a timestamp
            date_add( $now, date_interval_create_from_date_string( $date ) );
            return $now->format( $format );
        }
    }

    protected function generate_xml( $array = array(), $module = '' ) {

        if ( empty( $array ) || empty( $module ) ) {

            $this->error_array[ 'generate_xml' ] = __( 'The xml array data or module name is missing in function generate_xml', 'ninja-forms-zoho-crm' );

            return false;
        }

        $xml_data = '<' . $module . '><row no="1">';

        foreach ( $array as $key => $value ) {

            /*
             * NF3 escapes textarea tags; when these get sent to Zoho, these
             * tags appear as text.  The new default function is to decode
             * and then strip these tags, then escape the result
             * 
             * A filter allows one to keep the new functionality
             */
            $keep_tags = apply_filters('nfzohocrm_keep_html_tags', FALSE);
            
            if(!$keep_tags){
                $decoded = html_entity_decode($value);
                $stripped = wp_strip_all_tags($decoded);
                $value = esc_html($stripped);
            }
            
            $xml_data .= '<FL val="' . $key . '">' . $value . '</FL>';
        }

        $xml_data .= '</row></' . $module . '>';

        $this->validated_request[ $module ] = $xml_data;
        update_option('lb3test',$xml_data);
        return true;
    }

    protected function insert_record( $xml_data, $module = '' ) {//insert record via xml into requested module
        // build command url
        $command_url = $this->base_url . '/' . $module . '/insertRecords?';


        // build body array

        $body_array = array();  //initialize body array
        $body_array[ 'authtoken' ] = $this->authtoken;
        $body_array[ 'scope' ] = $this->scope;
        $body_array[ 'wfTrigger' ] = $this->wftrigger;
        $body_array[ 'isApproval' ] = $this->isapproval;
        $body_array[ 'newFormat' ] = 1;
        $body_array[ 'xmlData' ] = $xml_data;


        /* --- communicate and return response --- */

        $this->raw_response[ $module ] = wp_remote_post(
                $command_url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => FALSE,
            'body' => $body_array,
                )
        );
    }

    protected function process_response( $module = '' ) {

        if ( is_wp_error( $this->raw_response[ $module ] ) ) {

            $this->error_array[ $module ][ 'wp_error' ] = $this->raw_response[ $module ]->get_error_message();

            return false;
        }


        $json_array = json_decode( $this->raw_response[ $module ][ 'body' ], TRUE );

        $this->processed_response[ $module ] = $json_array;

        if ( isset( $json_array[ 'response' ][ 'result' ][ 'message' ] ) ) {

            $this->new_record_id_array[ $module ][ 'message' ] = $json_array[ 'response' ][ 'result' ][ 'message' ];
        } elseif ( isset( $json_array[ 'response' ][ 'error' ] ) ) {

            $this->new_record_id_array[ $module ][ 'message' ] = $json_array[ 'response' ][ 'error' ][ 'code' ] . ' - ' . $json_array[ 'response' ][ 'error' ][ 'message' ];
        }


        // store response values in the new record id array
        if ( isset( $json_array[ 'response' ][ 'result' ][ 'recorddetail' ][ 'FL' ] ) && is_array( $json_array[ 'response' ][ 'result' ][ 'recorddetail' ][ 'FL' ] ) ) {

            foreach ( $json_array[ 'response' ][ 'result' ][ 'recorddetail' ][ 'FL' ] as $array ) {

                $this->new_record_id_array[ $module ][ $array[ 'val' ] ] = $array[ 'content' ];
            }
        }

        $this->status_update .= 'Module - ' . $module . ' : ';
        $this->status_update .= $this->new_record_id_array[ $module ][ 'message' ];

        if ( isset( $this->new_record_id_array[ $module ][ "Created Time" ] ) ) {

            $this->status_update .= ' at: ' . $this->new_record_id_array[ $module ][ "Created Time" ];
        }

        $this->status_update .= '<br />';



        return true;
    }


    /*
      GETS AND SETS
      -------------------- */

    public function get_raw_request_array() {

        if ( empty( $this->raw_request_array ) ) {
            return false;
        } else {
            return $this->raw_request_array;
        }
    }

    public function get_processed_response() {

        if ( empty( $this->processed_response ) ) {
            return false;
        } else {
            return $this->processed_response;
        }
    }

    public function get_error_array() {

        if ( empty( $this->error_array ) ) {
            return false;
        } else {
            return $this->error_array;
        }
    }

    public function get_status_update() {

        if ( empty( $this->status_update ) ) {
            return false;
        } else {
            return $this->status_update;
        }
    }

    public function get_new_record_id_array(){
        
                if ( empty( $this->new_record_id_array ) ) {
            return false;
        } else {
            return $this->new_record_id_array;
        }
    }
}
