<?php

/**
 * Manages the retrieval and optional storage data
 *
 * Some data is stored by Ninja_Forms() but other is handled directly by
 * NF_ZohoCRM(); this class retrieves data from the various locations.
 * Also handles the storage of any data not managed by Ninja_Forms()
 *
 * @author stuartlb3
 */
class ZohoCRM_StoredData {

    /**
     * All Ninja Forms settings
     *
     * Manually loaded to ensure functionality if Ninja_Forms isn't loaded
     *
     * @var array
     */
    protected $ninja_forms_settings;

    /**
     * Array of OAuth tokens manually stored in options table
     * @var array
     */
    protected $oauth_tokens;

    /**
     * Record of communication through the API
     * @var array
     */
    protected $comm_data;

    /**
     * Configured default comm data structure to ensure keys are set
     * @var array
     */
    protected $default_comm_data;

    /**
     * Array of stored credentials for API access presented as keyed array
     *
     * Keys:
     *
     * authtoken , client_id, client_secret, authorization_code, access_token, refresh_token
     * @var array
     */
    protected $credentials;

    public function __construct() {

        $this->default_comm_data = NF_ZohoCRM::config( 'CommDataStructure' );

        $this->loadStoredData();
    }

    /**
     * Call methods that load stored data with defaults values as needed
     */
    protected function loadStoredData() {

        $this->ninja_forms_settings = get_option( 'ninja_forms_settings' );

        $this->oauth_tokens = get_option( NF_ZohoCRM()->constants->oauth_option_key );

        $this->loadCommData();

        $this->loadCredentials();
    }

    /**
     * Loads comm data and adds default config values if not present
     */
    protected function loadCommData() {

        $retrieved = get_option( NF_ZohoCRM()->constants->comm_data );

        if( is_array( $retrieved)){
            
            $this->comm_data = $retrieved + $this->default_comm_data;
        }else{
             $this->comm_data = $this->default_comm_data;
        }
       
    }

    /**
     * Loads stored credential variables and constructs keyed array from values
     */
    protected function loadCredentials() {

        $this->credentials = array(
            NF_ZohoCRM()->constants->auth_token  => $this->getNinjaFormSetting( NF_ZohoCRM()->constants->auth_token ),
            NF_ZohoCRM()->constants->client_id => $this->getNinjaFormSetting( NF_ZohoCRM()->constants->client_id ),
            NF_ZohoCRM()->constants->client_secret => $this->getNinjaFormSetting( NF_ZohoCRM()->constants->client_secret ),
            NF_ZohoCRM()->constants->authorization_code  => $this->getNinjaFormSetting( NF_ZohoCRM()->constants->authorization_code ),
            NF_ZohoCRM()->constants->refresh_token  => $this->getOauthTokens( NF_ZohoCRM()->constants->refresh_token ),
            NF_ZohoCRM()->constants->access_token  => $this->getOauthTokens( NF_ZohoCRM()->constants->access_token ),
            NF_ZohoCRM()->constants->api_domain  => $this->getOauthTokens( NF_ZohoCRM()->constants->api_domain ),
        );
    }

    /**
     * Retrieves value stored by Ninja_Forms
     * @param string $key
     * @return mixed
     */
    protected function getNinjaFormSetting( $key ) {

        $value = null;

        if ( isset( $this->ninja_forms_settings[ $key ] ) ) {

            $value = $this->ninja_forms_settings[ $key ];
        }

        return $value;
    }

    protected function getOauthTokens( $key ) {

        $value = null;

        if ( isset( $this->oauth_tokens[ $key ] ) ) {

            $value = $this->oauth_tokens[ $key ];
        }

        return $value;
    }

    /**
     * Returns Communication Data
     *
     * Keys built form CommDataStructure config
     *
     * @return array
     */
    public function commData() {

        return $this->comm_data;
    }

    /**
     * Erases comm data, resets to default values, does NOT write to DB
     *
     * Defaults from config CommDataStructure
     * @see ZohoCRM_StoredData::updateCommData()
     */
    public function resetCommData(){

        $this->comm_data = $this->default_comm_data;
    }

    /**
     * Modify the comm data, but does not update the database
     *
     * If no key specified, treat parameter as full comm data array, else
     * keep the current comm data and modify only the selected key
     *
     * Adds default values to ensure all required keys are set
     *
     * @param mixed $modified_comm_data Array of comm data to be modified
     * @param string $key Optional key to modify specific element in comm data array
     * @param boolean $append Add the value as an array element; default is true
     */
    public function modifyCommData( $modified_comm_data, $key = '', $append = TRUE ) {

        if ( '' === $key ) {

            $array = $modified_comm_data;
        } else {

            $array = $this->comm_data;

            if ( $append ) {

                $array[ $key ][] = $modified_comm_data;
            } else {

                $array[ $key ] = $modified_comm_data;
            }
        }

        $validated = $array + $this->default_comm_data;

        $this->comm_data = $validated;
    }

    /**
     * Updates the database with the comm data
     */
    public function updateCommData() {

        update_option( NF_ZohoCRM()->constants->comm_data, $this->comm_data );
    }

    /**
     * Returns array of API credentials
     *
     * @return array
     */
    public function credentials() {

        return $this->credentials;
    }

    /**
     * Modify the Refresh token, does not update DB
     * @param string $value
     * @see updateOauthToken
     */
    public function modifyRefreshToken( $value ) {

        $this->oauth_tokens[ NF_ZohoCRM()->constants->refresh_token ] = $value;
    }

        /**
     * Modify the Refresh token, does not update DB
     * @param string $value
     * @see updateOauthToken
     */
    public function modifyApiDomain( $value ) {

        $this->oauth_tokens[ NF_ZohoCRM()->constants->api_domain ] = $value;
    }
    
    public function updateOauthToken() {

        update_option( NF_ZohoCRM()->constants->oauth_option_key, $this->oauth_tokens );
    }

    /**
     * Returns the stored advanced codes for the plugin
     *
     * Stored as comma-delimited, explodes into array of string commands
     *
     * @return array Advanced codes as array
     */
    public function advancedCommands() {

        $settings_key = NF_ZohoCRM()->constants->advanced_commands_key;

        $advanced_codes_array = array(); //initialize

        if ( isset( $this->ninja_forms_settings[ $settings_key ] ) ) {

            $advanced_codes_setting = $this->ninja_forms_settings[ $settings_key ];

            $advanced_codes_array = array_map( 'trim', explode( ',', $advanced_codes_setting ) );
        }

        return $advanced_codes_array;
    }

    /**
     * Given an advanced command, evaluates if it is set or not
     * @param string $command
     * @return bool TRUE is command is set, FALSE if not set
     */
    public function isAdvancedCommandSet( $command = '' ) {

        $advanced_codes_array = $this->advancedCommands();

        if ( in_array( $command, $advanced_codes_array ) ) {

            $evaluation = TRUE;
        } else {

            $evaluation = FALSE;
        }

        return $evaluation;
    }

    /**
     * Searches advanced commands for variable values and returns variable
     *
     * Given a prefixed command, returns integer suffix, false on fail
     *
     * @param string $prefix
     * @return int|bool Description
     */
    public function variableAdvancedCommand( $prefix = '' ) {

        $return = FALSE;

        $advanced_commands_array = $this->advancedCommands();

        foreach ( $advanced_commands_array as $advanced_command ) {

            if ( 0 === strpos( $advanced_command, $prefix ) ) {

                $return = intval( str_replace( $prefix, '', $advanced_command ) );
                
                break;
            }
        }

        return $return;
    }

}
