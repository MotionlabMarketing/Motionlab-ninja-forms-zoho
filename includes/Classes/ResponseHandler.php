<?php

/**
 * Evaluates response and extract pertinent information
 *
 * @author stuartlb3
 */
class ZohoCRM_ResponseHandler
{

    /**
     * Array of all response information
     * @var array
     */
    protected $response_array;

    /**
     * Response from wp_remote_post
     *
     * @var array
     */
    protected $raw_response;

    /**
     * Stops the processing of evaluateResposne
     * @var boolean
     *
     */
    protected $continue_evaluate_response;
    protected $body;

    /**
     * Creates response handler
     *
     * Use setRawResponse and getResponseArray for information in and out
     */
    public function __construct()
    {
        $this->initializeProperties();
    }

    /**
     * Initialize values before evaluating incoming raw response
     *
     * Enables same object to be used multiple times
     */
    protected function initializeProperties()
    {
        $this->response_array = NF_ZohoCRM::config('ResponseArray');

        $this->continue_evaluate_response = TRUE;
    }

    /**
     * Evaluate the insertRecord response to extract all pertinent information
     * 
     * Data is added to the Response Array for standardized handling
     * 
     * This method is not used for GETting records as the response construct
     * is different.
     *
     * @param array $raw_response
     */
    public function evaluateResponse($raw_response)
    {
        $this->initializeProperties();

        $this->raw_response = $raw_response;

        $this->wpErrorCheck();

        if ($this->continue_evaluate_response) {

            $this->extractInsertRecordBody();

            $this->insertRecordRejectionDetection();
        }

        if ($this->continue_evaluate_response) {

            $this->extractInsertRecordResponse();
        }
    }

    /**
     * Evaluate the GET record response to extract all pertinent information
     * 
     * Data is added to the Response Array for standardized handling
     * 
     * @param array $raw_response Raw response from CRMm
     */
    public function evaluateGETRecordResponse($raw_response)
    {
        $this->initializeProperties();

        $this->raw_response = $raw_response;

        $this->wpErrorCheck();

        if ($this->continue_evaluate_response) {
            $this->extractGETRecordBody();
        }

        if ($this->continue_evaluate_response) {
            $this->extractGETRecordResponse();
        }
    }

    /**
     * Given body array of rejection response, extracts messages to response array
     * @param array $body Body array from wp_remote_post
     */
    protected function extractRejectionMessages()
    {
        if (isset($this->body[ 'code' ])) {

            $this->response_array[ 'error_codes' ][] = $this->body[ 'code' ];
        }

        if (isset($this->body[ 'details' ]) && is_array($this->body[ 'details' ])) {

            foreach ($this->body[ 'details' ] as $key => $value) {
                $this->response_array[ 'error_messages' ][] = $key . ' : ' . $value;
            }
        }

        $this->response_array[ 'status' ] = __('Rejected by API', 'ninja-forms-zoho-crm');
    }

    /**
     * Check for WP_Error; add error messages to response array
     */
    protected function wpErrorCheck()
    {
        if (is_wp_error($this->raw_response)) {

            $this->setErrorResponses('wp_error');

            $error_mesages = $this->raw_response->get_error_messages();

            if (!empty($error_mesages)) {

                $this->response_array[ 'error_messages' ] = $error_mesages;
            }

            $this->response_array[ 'status' ] = __('WordPress error', 'ninja-forms-zoho-crm');
        }
    }

    /**
     * Extract the body message from known insertRecord constructs
     *
     * Successful communication (not always successful outcome) has indexed
     * array of assoc. array responses keyed under 'data'
     *
     * Unsuccessful communication is associative array.  Both assoc. arrays
     * have the following keys
     * Keys:
     *
     * code, details, message, status
     * 
     * This method is used when attempting to create a new record.  It is not
     * used for GETting records as the construct is different
     */
    protected function extractInsertRecordBody()
    {
        $body_array = json_decode($this->raw_response[ 'body' ], TRUE);

        if (isset($body_array[ 'data' ][ 0 ])) {

            $this->body = $body_array[ 'data' ][ 0 ];
        } elseif (isset($body_array[ 'code' ])) {

            $this->body = $body_array;
        }
    }

    /**
     * Decodes the response into a known array structure or sets error
     */
    protected function extractGETRecordBody()
    {
        $decode = json_decode($this->raw_response['body'], TRUE);

        if (!is_array($decode) || empty($decode)) {
            $this->setErrorResponses('API');
        } else {
            $default_get_record_response = NF_ZohoCRM()->config('GETRecordDefaultResponse');

            $this->body = array_merge($default_get_record_response, $decode);
        }
    }

    /**
     * Populates response array with response values of successful communication
     * 
     * Note that results may be empty, but it is still valid
     */
    protected function extractGETRecordResponse()
    {
        $this->response_array[ 'api_data' ] = $this->body;

        $this->response_array[ 'timestamp' ] = date(NF_ZohoCRM()->constants->date_format);

        $this->response_array[ 'status' ] = __('Records successfully retrieved by API', 'ninja-forms-zoho-crm');
    }

    /**
     * Checks for rejection by API when attempting to insertRecord
     *
     * Must be done after WP_error check; assumes a well-formed API response
     */
    protected function insertRecordRejectionDetection()
    {
        if (isset($this->body[ 'status' ]) && 'error' === $this->body[ 'status' ]) {

            $this->setErrorResponses('api_error');

            $this->extractRejectionMessages();
        }
    }

    /**
     * Extract ResponseArray data from insertRecord response
     */
    protected function extractInsertRecordResponse()
    {
        if (isset($this->body[ 'details' ][ 'id' ])) {
            $this->response_array[ 'api_data' ][ 'id' ] = $this->body[ 'details' ][ 'id' ];
        }

        if (isset($this->body[ 'details' ][ 'Created_Time' ])) {
            $this->response_array[ 'timestamp' ] = $this->body[ 'details' ][ 'Created_Time' ];
        }

        $this->response_array[ 'status' ] = __('Request accepted by API', 'ninja-forms-zoho-crm');
    }

    /**
     * Set standard error values in response array
     * @param string $error_type Specific error type - WP or API
     */
    protected function setErrorResponses($error_type)
    {
        $this->response_array[ 'error' ] = TRUE;

        $this->response_array[ $error_type ] = TRUE;

        $this->response_array[ 'timestamp' ] = date(NF_ZohoCRM()->constants->date_format);

        $this->continue_evaluate_response = FALSE;
    }

    /**
     * Returns the Response Array
     * @return array Response Array configured by ResponseArray config file
     */
    public function getResponseArray()
    {
        return $this->response_array;
    }

}
