<?php

/**
 * Communication object for Zoho API v2
 *
 * @author stuartlb3
 */
class ZohoCRM_Comm {

    /**
     * Full set of AuthParams credentials
     * @var array
     * @see ZohoCRM_AuthParams::$credentials
     */
    protected $credentials; 

    /**
     * Header array used in making requests
     * @var array
     */
    protected $header = array();

    /**
     * Default communication arguments used in wp_remote_post
     * @var array
     */
    protected $default_comm_args;

    /**
     * @param array $credentials Full set of AuthParams credentials
     */
    public function __construct( $credentials ) {

        $this->credentials = $credentials;

        $this->authorizationHeader();

        $this->setDefaultCommArgs();
    }
    
    /**
     * Communicate through API to make desired request
     *
     * @param string $request_type POST, GET, PUT
     * @param string $endpoint URL for request endpoint
     * @param string $json JSON request
     */
    public function makeCommRequest( $request_type, $endpoint, $json=''){

       $url = $this->buildURL($endpoint);

       $args = $this->default_comm_args;

       $args['method'] = $request_type;

       if ('' !== $json) {
            $args[ 'body' ] = $json;
        }

        $args['headers']= $this->header;

       $response = wp_remote_post( $url, $args );

       return $response;
    }

    /**
     * Adds authorization parameter to header
     *
     * For API v2, it array( 'Authorization' =>'Zoho-oauthtoken {access_token}' )
     */
    protected function authorizationHeader() {

        $authorization = 'Zoho-oauthtoken ' . $this->credentials[ NF_ZohoCRM()->constants->access_token ];

        $this->header[ 'Authorization' ] = $authorization;

        $this->header[ 'Content-Type' ] = 'application/json';
    }

    /**
     * Set the default communication args for wp_remote_post
     */
    protected function setDefaultCommArgs() {

        $this->default_comm_args = array(
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => TRUE, // Add advanced command to turn off
        );
    }


    /**
     * Builds and returns the full URL for request
     * @param string $endpoint API endpoint for request
     * @return string Full URL for request
     */
    protected function buildURL( $endpoint ) {

        if (isset($this->credentials[ 'api_domain' ]) && 0 < strlen($this->credentials[ 'api_domain' ])) {

            $api_domain = $this->credentials[ 'api_domain' ];
        } else {

            $is_eu = NF_ZohoCRM()->advanced_commands->isAdvancedCommandSet('eu_endpoint');

            if ($is_eu) {

                $api_domain = 'https://www.zohoapis.eu';
            } else {

                $api_domain = 'https://www.zohoapis.com';
            }
        }


        $scope_version = 'crm/v2';

        $url = $api_domain . '/' . $scope_version . '/' . $endpoint;

        return $url;
    }

    /**
     * Sets a content type for the request
     * 
     * Default is application/json; using this method overrides default
     * 
     * @param string $content_type
     */
    public function setContentType( $content_type){
        
        $this->header[ 'Content-Type' ] = $content_type;
    }
}
