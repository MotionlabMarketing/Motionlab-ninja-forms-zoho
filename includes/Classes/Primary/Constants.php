<?php

/**
 * Constants for use throughout extension
 *
 * Set as protected properties with no ability to set
 * 
 * @property string $authlevel_v2 Zoho CRM authorization using V2 OAuth credentials
 * @property string $authlevel_v1 Zoho CRM authorization using V1 authtoken
 * @property string $authlevel_none No Zoho CRM authorization credentials available
 * @property string $date_format Date format for API communication
 * @property string $settings_group Key under which plugin settings are stored
 * 
 * @property string $auth_token Key for Authtoken stored in NF Plugin Settings
 * @property string $client_id OAuth 2 Client ID stored in NF Plugin Settings
 * @property string $client_secret OAuth 2 Client Secret stored in NF Plugin Settings
 * @property string $authorization_code  OAuth 2 Authorization Code stored in NF Plugin Settings
 * @property string $comm_status Communication status stored in NF Plugin Settings
 * @property string $errors Field for holding error messages stored in NF Plugin Settings
 * 
 * @property string $advanced_commands_key Key for advanced codes stored in NF Plugin Settings
 * 
 * @property string $field_map_array Key for field map array stored in NF Plugin Settings
 * @property string $structured_request_array Key for structured request array stored in NF Plugin Settings
 * @property string $structured_response Key for structured response stored in NF Plugin Settings
 * @property string $raw_response Key for raw response stored in NF Plugin Settings
 * 
 * @property string $manual_field_map Key for displaying a manual copy of the field map
 * @property string $retrieved_field_names Key for display a list of all fields retrieved from CRM
 * 
 * @property string $oauth_option_key Key under which OAuth credentials are stored in Options table
 * @property string $refresh_token OAuth 2 Refresh token displayed in settings using HTML
 * @property string $access_token OAuth 2 Access token displayed in settings using HTML
 * @property string $api_domain OAuth 2 Domain name for the API
 * 
 * @property string $comm_data Key for comm_data stored in options table
 * 
 * @property string $field_map_action_key Action Settings key for field map option repeater
 *
 * @property string $multiple_module_delimiter Delimiter for indicating multiple module entries
 */
class ZohoCRM_Constants {
    /*
     * -------------------
     * Constants
     * ------------------- 
     */
    protected $authlevel_v2 = 'authlevel_v2';
    protected $authlevel_v1 = 'authlevel_v1';
    protected $authlevel_none = 'authlevel_none';
    protected $date_format = 'Y-m-d';

    /*
     * -------------------
     * Ninja Forms Settings Keys
     * ------------------- 
     */
    protected $settings_group = 'zohocrm';
    protected $auth_token = 'nfzohocrm_authtoken';
    protected $client_id = 'nfzohocrm_client_id';
    protected $client_secret = 'nfzohocrm_client_secret';
    protected $authorization_code = 'nfzohocrm_authorization_code';
    protected $advanced_commands_key = 'nfzohocrm_advanced_codes';
    protected $comm_status = 'comm_status';
    protected $errors = 'errors';
    protected $field_map_array =  'field_map_array';
    protected $structured_request_array = 'request_array';
    protected $structured_response = 'structured_response';
    protected $raw_response = 'raw_response';
    protected $manual_field_map = 'manual_field_map';
    protected $retrieved_field_names = 'retrieved_field_names';
      /*
     * -------------------
     * OAuth Credential Keys
     * ------------------- 
     */    
    protected $oauth_option_key = 'nfzohocrm_oauth_tokens';
    protected $refresh_token = 'nfzohocrm_refresh_token';
    protected $access_token = 'nfzohocrm_access_token';
    protected $api_domain = 'nfzohocrm_api_domain';

    /*
     * -------------------
     * Option table keys
     * ------------------- 
     */
    protected $comm_data = 'nfzohocrm_comm_data';

    /*
     * -------------------
     * Action keys
     * ------------------- 
     */
    protected $field_map_action_key = 'zoho_field_map';

    
    protected $multiple_module_delimiter = '::';
    
    
    public function __get( $property ) {

        $value = FALSE;

        if ( property_exists( $this, $property ) ) {

            $value = $this->$property;
        }

        return $value;
    }

}
