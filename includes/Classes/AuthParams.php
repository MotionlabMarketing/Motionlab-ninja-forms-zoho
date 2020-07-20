<?php

/**
 * Extracts authorization parameters and provides them for CRM access
 *
 * Helps with troubleshooting and support messages; may determine auth level
 * based on extracted auth parameters
 *
 * @author stuartlb3
 */
class ZohoCRM_AuthParams {

    /**
     * Array of available credentials
     *
     * Keys:
     *
     *  authtoken , client_id, client_secret, authorization_code, access_token, refresh_token
     * @var array
     * @see ZohoCRM_StoredData::$credentials
     */
    protected $credentials;

    /**
     * The authorization level determined by highest setting stored
     * @var string
     */
    protected $auth_level;

    /**
     * Message describing the auth parameter status
     * @var string
     */
    protected $status = '';

    /**
     * Error message in auth parameters
     * @var string
     */
    protected $error_message = '';

    public function __construct() {

        $this->retrieveStoredCredentials();

        $this->determineAuthLevel();
    }

    /**
     * Retrieves stored credentials
     * @return array
     */
    protected function retrieveStoredCredentials() {

        $this->credentials = NF_ZohoCRM()->stored_data->credentials();
    }

    /**
     * Sets the auth level based on highest authority stored in settings
     *
     * Authtoken uses Zoho API v1
     */
    protected function determineAuthLevel() {

        if ( 0 < strlen( $this->credentials[ NF_ZohoCRM()->constants->client_id ] ) &&
                0 < strlen( $this->credentials[ NF_ZohoCRM()->constants->client_secret ] ) ) {

            $this->auth_level = NF_ZohoCRM()->constants->authlevel_v2;

            $this->setStatusAuthlevelV2();

            $this->generateAccessToken();
        } elseif ( 0 < strlen( $this->credentials[ NF_ZohoCRM()->constants->auth_token] ) ) {

            $this->auth_level = NF_ZohoCRM()->constants->authlevel_v1;

            $this->setStatusAuthlevelV1();
        } else {
            $this->auth_level = NF_ZohoCRM()->constants->authlevel_none;

            $this->setStatusAuthlevelNone();
        }
    }

    /**
     * Requests access token from Token class
     */
    protected function generateAccessToken() {

        $token_object = NF_ZohoCRM()->factory->oauthTokens( $this->credentials );

        $token_object->generateAccessToken();

        $this->credentials[ NF_ZohoCRM()->constants->access_token ] = $token_object->accessToken();
    }

    /**
     * Set the AuthLevel 2 message
     */
    protected function setStatusAuthlevelV2() {

        $this->status = __( 'Using Zoho OAuth credentials for API V2', 'ninja-forms-zoho-crm' );
    }

    /**
     * Set the Authlevel 1 message
     */
    protected function setStatusAuthlevelV1() {

        $this->status = __( 'Using Zoho Authtoken API V1', 'ninja-forms-zoho-crm' );
    }

    /**
     * Set the error message for missing authorization
     */
    protected function setStatusAuthlevelNone() {

        $this->status .= __( 'The authorization keys are missing/incomplete.  Please check your Ninja Forms Zoho settings', 'ninja-forms-zoho-crm' );
    }

    /**
     * Returns true if a valid auth level has been determined
     *
     * @return boolean
     */
    public function isAuthorized() {

        if ( $this->auth_level === NF_ZohoCRM()->constants->authlevel_v1 ||
                $this->auth_level === NF_ZohoCRM()->constants->authlevel_v2 ) {

            $is_authorized = TRUE;
        } else {

            $is_authorized = FALSE;
        }

        return $is_authorized;
    }

    /**
     * Returns the status message of the AuthParams Object
     *
     * @return string
     */
    public function status() {

        return $this->status;
    }

    /**
     * Returns the auth level determined by credentials extracted
     *
     * @return string
     */
    public function authLevel() {

        return $this->auth_level;
    }

    /**
     * Returns current AuthParameter credentials, both stored and generated
     *
     * Specific key (optional) returns string, empty if key doesn't exist
     * @param string $key Keyed value to return (optional)
     * @return array | string
     */
    public function credentials( $key = '' ) {

        $value = $this->credentials;

        if ( '' !== $key ) {

            if ( isset( $this->credentials[ $key ] ) ) {

                $value = $this->credentials[ $key ];
            } else {

                $value = '';
            }
        }

        return $value;
    }

}
