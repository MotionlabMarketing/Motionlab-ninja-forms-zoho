<?php

/**
 * Generates OAuth tokens for immediate access and future refreshes
 *
 */
class ZohoCRM_Tokens
{

    /**
     * Array of credentials
     * @var array
     * @see ZohoCRM_StoredData::$credentials
     * @see ZohoCRM_StoredData::loadCredentials()
     */
    protected $credentials;

    /**
     *
     * @var array Array of messages built during token processing
     */
    protected $message_array;

    /**
     * URL for token requests
     * @var string
     */
    protected $token_url;

    public function __construct($credentials)
    {

        $is_eu = NF_ZohoCRM()->advanced_commands->isAdvancedCommandSet('eu_endpoint');

        if ($is_eu) {

            $this->token_url = 'https://accounts.zoho.eu/oauth/v2/token';
        } else {
 
            $this->token_url = 'https://accounts.zoho.com/oauth/v2/token';
        }

        $token_array = $this->returnDefaultTokenArray();

        $this->credentials = array_merge($token_array, $credentials);
    }

    /**
     * Generates access token
     *
     * Checks if refresh token is stored and if not attempts to generate both
     * tokens using authorization code request type
     */
    public function generateAccessToken()
    {

        if (0 < strlen($this->credentials[ NF_ZohoCRM()->constants->authorization_code ])) {

            $this->generateTokensFromAuthCode();
        } else {

            $this->generateAccessTokenFromRefreshToken();
        }
    }

    /**
     * Generates access token from existing refresh token
     */
    public function generateAccessTokenFromRefreshToken()
    {

        $parameter_array = array(
            'grant_type' => 'refresh_token',
            'client_id' => $this->credentials[ NF_ZohoCRM()->constants->client_id ],
            'client_secret' => $this->credentials[ NF_ZohoCRM()->constants->client_secret ],
            'refresh_token' => $this->credentials[ NF_ZohoCRM()->constants->refresh_token ]
        );

        $post_args = $this->returnDefaultPostArgs();

        $query_string = http_build_query($parameter_array);

        $response = wp_remote_post($this->token_url . '?' . $query_string, $post_args);

        self::handleTokenResponse($response);
    }

    /**
     * Generates Refresh and Access Tokens from Authorization Code
     */
    public function generateTokensFromAuthCode($redirect = '')
    {
        if (0 < strlen($this->credentials[ NF_ZohoCRM()->constants->authorization_code ])) {

            $param_redirect_uri = apply_filters('nfzohocrm_redirect_uri', 'https://accounts.zoho.com');
            
            $parameter_array = array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->credentials[ NF_ZohoCRM()->constants->client_id ],
                'client_secret' => $this->credentials[ NF_ZohoCRM()->constants->client_secret ],
                'code' => $this->credentials[ NF_ZohoCRM()->constants->authorization_code ],
                'redirect_uri' => $param_redirect_uri,
            );

            $post_args = self::returnDefaultPostArgs();

            $query_string = http_build_query($parameter_array);

            $response = wp_remote_post($this->token_url . '?' . $query_string, $post_args);

            $this->handleTokenResponse($response);

            $this->deleteExpendedAuthorizationCode();

            NF_ZohoCRM()->stored_data->modifyRefreshToken($this->credentials[ NF_ZohoCRM()->constants->refresh_token ]);

            NF_ZohoCRM()->stored_data->updateOauthToken();
        }

        if (0 < strlen($redirect)) {

            wp_redirect($redirect);
            exit;
        }
    }

    /**
     * Removes the authorization code from settings after generating token
     */
    protected function deleteExpendedAuthorizationCode()
    {
        if (!class_exists('Ninja_Forms')) {
            return;
        }

        Ninja_Forms()->update_setting(NF_ZohoCRM()->constants->authorization_code, '');
    }

    /**
     * Evaluates input for successful refresh token and calls update method
     *
     * If error or refresh token not available, uses default values.
     *
     * @param object|array $raw_response Full wp_remote_post response
     * @return array Keyed array of tokens, with default value backup
     */
    protected function handleTokenResponse($raw_response)
    {
        if (is_wp_error($raw_response)) {

            $this->handleWPError($raw_response);
        } else {

            $response_array = json_decode($raw_response[ 'body' ], TRUE);

            $this->handleResponseArray($response_array);
        }
    }

    /**
     * Evaluate response array for final handling
     * @param array $response_array
     */
    protected function handleResponseArray($response_array)
    {
        if (isset($response_array[ 'error' ])) {

            $this->handleResponseArrayError($response_array);
        } else {

            $this->extractTokens($response_array);
        }
    }

    /**
     * Extracts Refresh and/or Access token to credentials
     * @param array $response_array
     */
    protected function extractTokens($response_array)
    {
        if (isset($response_array[ 'access_token' ])) {

            $this->credentials[ NF_ZohoCRM()->constants->access_token ] = $response_array[ 'access_token' ];
        }

        if (isset($response_array[ 'refresh_token' ])) {

            $this->credentials[ NF_ZohoCRM()->constants->refresh_token ] = $response_array[ 'refresh_token' ];

            NF_ZohoCRM()->stored_data->modifyRefreshToken($response_array[ 'refresh_token' ]);
        }
//api_domain

        if (isset($response_array[ 'api_domain' ])) {

            $this->credentials[ NF_ZohoCRM()->constants->api_domain ] = $response_array[ 'api_domain' ];

            NF_ZohoCRM()->stored_data->modifyApiDomain($response_array[ 'api_domain' ]);
        }

        NF_ZohoCRM()->stored_data->updateOauthToken();

        $this->message_array = array( 'The refresh token was successfully generated and stored ' . date('Y-m-d h:i:s') );
    }

    /**
     * Extract rejection error messages from response array
     * @param array $response_array
     */
    protected function handleResponseArrayError($response_array)
    {
        $user_friendly = array( /* NF_CapsuleCRM_Constants::ACTION_REQUIRED. */ 'The request was rejected by the CRM ' . date('Y-m-d h:i:s') );

        $this->message_array = array_merge($user_friendly, array( $response_array[ 'error' ] ));
    }

    /**
     * Extracts errors from WP_Error object
     *
     * @param array $raw_response Response from wp_remote_post
     */
    protected function handleWPError($raw_response)
    {
        $wp_errors = $raw_response->get_error_messages();

        $user_friendly = array( /* NF_CapsuleCRM_Constants::ACTION_REQUIRED. */'There was an internal WP error trying to communicate ' . date('y-m-d h:i:s') );

        $this->message_array = array_merge($user_friendly, $wp_errors);
    }

    /**
     * Returns default token array
     * @return array
     */
    protected function returnDefaultTokenArray()
    {
        return array( NF_ZohoCRM()->constants->refresh_token => '', NF_ZohoCRM()->constants->access_token => '' );
    }

    /**
     * Returns default post arguments
     * @return array
     */
    protected function returnDefaultPostArgs()
    {
        return array(
            'timeout' => 45,
            'redirection' => 0,
            'httpversion' => '1.0',
            'sslverify' => TRUE,
        );
    }

    public function accessToken()
    {

        return $this->credentials[ NF_ZohoCRM()->constants->access_token ];
    }

}
