<?php

return array(
    'api_data' => array(), // Response data
    'timestamp' => '',
    /**
     * String description of result
     */
    'status'=>'',
    'error' => false, // Any type of error
    'wp_error' => false, // Wordpress error object
    'api_error' => false, // Comm error using API
    /**
     * Indexed array of error codes string elements
     * returned from API or internally generated
     */
    'error_codes' => array(),
    /**
     * Indexed array of Error messages string elements
     * Returned from API or internally generated
     */
    'error_messages' => array(),
    'headers' => array(),
    'response_body' => '' // Full Response
);
