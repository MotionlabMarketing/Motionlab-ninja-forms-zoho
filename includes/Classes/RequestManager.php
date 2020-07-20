<?php

/**
 * Given a Request Array and Comm Object, makes requests and manages responses
 *
 * @author stuartlb3
 */
class ZohoCRM_RequestManager
{

    /**
     * Request array for which to make requests
     * @var array
     */
    protected $request_array;

    /**
     * Communication object for making requests
     * @var ZohoCRM_Comm
     */
    protected $comm_object;

    /**
     * Module configuration with instructions for requests
     * @var array
     */
    protected $module_config;

    /**
     * Array of JSON requests constructed for requests
     *
     * @var array
     */
    protected $json_request_data;

    /**
     * Parameters extracted from request array
     * @var array
     */
    protected $parameters;

    /**
     * Array of raw responses
     * @var array
     */
    protected $raw_response;

    /**
     * Response handler object
     * @var ZohoCRM_ResponseHandler
     */
    protected $response_handler;

    /**
     * Response structured by configured ResponseArray
     * @var array
     */
    protected $structured_response = array();

    /**
     * Initialize the Request Manager
     * @param array $request_array Request array
     * @param ZohoCRM_Comm $comm_object
     */
    public function __construct($request_array, $comm_object)
    {
        $this->request_array = $request_array;

        $this->comm_object = $comm_object;

        $this->response_handler = NF_ZohoCRM()->factory->responseHandler();

        $this->module_config = NF_ZohoCRM::config('Modules');

        $this->extractParameters();
    }

    public function iterateRequests()
    {
        $max_index = NF_ZohoCRM()->field_map->maxModuleCount();

        foreach (array_keys($this->module_config) as $module_key) {

            $index = 0;

            while ($index <= $max_index) {

                if (!isset($this->request_array[ $module_key ][ $index ])) {

                    $index++;

                    continue;
                }

                $this->validateModule($module_key, $index);

                $this->linkParentModules($module_key, $index);
                
                $this->linkChildModules($module_key, $index);
                
                $this->constructJSONBody($module_key, $index);

                $this->makeCommRequest($module_key, $index);

                $this->evaluateRawResponse($module_key, $index);

                $this->appendStatus($module_key, $index);

                $this->appendErrors($module_key, $index);

                $index++;
            }
        }
    }

    /**
     * Extract parameters as property and unset from request array
     */
    protected function extractParameters()
    {
        if (isset($this->request_array[ 'Parameters' ])) {

            $this->parameters = $this->request_array[ 'Parameters' ];
            unset($this->request_array[ 'Parameters' ]);
        }
    }

    /**
     * Ensure required fields are set
     * @param string $module_key Key for module to validate
     * @param integer $index Index key for module
     */
    protected function validateModule($module_key, $index)
    {
        $this->addRequiredFields($module_key, $index);
    }

    /**
     * Ensures required fields are present in request array
     *
     * Checks module_config for required_fields array and adds missing fields
     * using default value in module_config
     *
     * @param string $module_key
     * @param integer $index
     * @return null
     */
    protected function addRequiredFields($module_key, $index)
    {
        if (!isset($this->module_config[ $module_key ][ 'required_fields' ])) {

            return;
        }

        foreach ($this->module_config[ $module_key ][ 'required_fields' ] as $required_field => $default_value) {

            if (!isset($this->request_array[ $module_key ][ $index ][ $required_field ])) {

                $this->request_array[ $module_key ][ $index ][ $required_field ] = $default_value;
            }
        }
    }

    /**
     * Check if Parent modules exist, insert link into array
     * @param string $module_key Key for module to link to parents
     * @param integer $index
     */
    protected function linkParentModules($module_key, $index)
    {
        if (!isset($this->module_config[ $module_key ][ 'parents' ])) {

            return;
        }

        foreach ($this->module_config[ $module_key ][ 'parents' ] as $parent_array) {

            if ('new_id' !== $parent_array[ 'parent_field' ]) {

                $this->insertParentData($module_key, $index, $parent_array);
            } else {
                $this->insertParentID($module_key, $index, $parent_array);
            }
        }
    }
/**
 * Check if children requests exist and insert into given module
 * 
 * Children requests are added as JSON into the parent request; these are
 * initially constructed as a separate request through the field mapping and
 * then inserted into the parent request as specified in the Module config.
 * 
 * There can be many child entries of the same module type, as in the case of
 * multiple Products for a quote or sales order
 * 
 * @param string $module_key
 * @param integer $index
 * @return null
 */
    protected function linkChildModules($module_key, $index)
    {
        if (!isset($this->module_config[ $module_key][ 'children' ] )) {

            return;
        }

        foreach ($this->module_config[ $module_key ][ 'children' ] as $child_array) {

            if (!isset($this->request_array[ $child_array[ 'child_module' ] ]) ||
                    !is_array($this->request_array[ $child_array[ 'child_module' ] ])) {

                continue;
            }
            
            foreach ($this->request_array[ $child_array[ 'child_module' ] ] as $child) {

                $this->request_array[ $module_key ][ $index ][ $child_array[ 'parent_field' ] ][] = $child;
            }

            unset($this->request_array[ $child_array[ 'child_module' ] ]);
        }
    }

    /**
     * Inserts parent field data into current request array module
     *
     * Used when parent field value is used to link
     *
     * @param string $module_key Key for module to link to parents
     * @param integer $index
     * @param array $parent_array Single array in module config parent
     */
    protected function insertParentData($module_key, $index, $parent_array)
    {
        $parent_index = null;

        $parent_module = $parent_array[ 'parent_module' ];

        $parent_field = $parent_array[ 'parent_field' ];

        $child_field = $parent_array[ 'child_field' ];

        if (isset($this->request_array[ $parent_array[ 'parent_module' ] ][ $index ])) {
            $parent_index = $index;
        } elseif (isset($this->request_array[ $parent_array[ 'parent_module' ] ][ 0 ])) {
            $parent_index = 0;
        }

        if (isset($this->request_array[ $parent_module ][ $parent_index ][ $parent_field ])) {

            $this->request_array[ $module_key ][ $index ][ $child_field ] = $this->request_array[ $parent_module ][ $parent_index ][ $parent_field ];
        }
    }

    /**
     * Inserts newly created parent id into current request array module
     *
     * Used when id of newly created parent is used to link
     *
     * @param string $module_key Key for module to link to parents
     * @param integer $index
     * @param array $parent_array Single array in module config parent
     */
    protected function insertParentID($module_key, $index, $parent_array)
    {
        $parent_index = null;

        $parent_module = $parent_array[ 'parent_module' ];

        $child_field = $parent_array[ 'child_field' ];

        if (isset($this->request_array[ $parent_array[ 'parent_module' ] ][ $index ])) {

            $parent_index = $index;
        } elseif (isset($this->request_array[ $parent_array[ 'parent_module' ] ][ 0 ])) {

            $parent_index = 0;
        }

        if (isset($this->structured_response[ $parent_module ][ $parent_index ][ 'api_data' ][ 'id' ])) {

            $parent_id = $this->structured_response[ $parent_module ][ $parent_index ][ 'api_data' ][ 'id' ];

            $this->request_array[ $module_key ][ $index ][ $child_field ][ 'id' ] = $parent_id;

            $this->request_array[ $module_key ][ $index ][ '$se_module' ] = $parent_module;
        }
    }

    /**
     * Construct JSON request body
     * @param string $module_key Key for module for which to construct JSON
     */
    protected function constructJSONBody($module_key, $index)
    {
        $array = array();

        $array[ 'data' ][] = $this->request_array[ $module_key ][ $index ];

//        $array['trigger']= array(); // determine how to handle triggers
        // previously only workflow and approval triggers were available and had to be
        // manually set.  Now, by default, all triggers, including blueprints will fire
        // may need a way to force 'off' triggers if desired

        $json = json_encode($array);

        $this->json_request_data[ $module_key ][ $index ] = $json;
    }

    /**
     * Make request through given comm object
     * @param string $module_key Key for module for which to make request
     */
    protected function makeCommRequest($module_key, $index)
    {
        $request_type = 'POST';

        $endpoint = $this->module_config[ $module_key ][ 'insert_endpoint' ];

        $json = $this->json_request_data[ $module_key ][ $index ];

        $this->raw_response[ $module_key ][ $index ] = $this->comm_object->makeCommRequest($request_type, $endpoint, $json);
    }

    /**
     * Evaluates the raw response and extract pertinent data
     * @param string $module_key Key for module to be evaluated
     */
    protected function evaluateRawResponse($module_key, $index)
    {
        $this->response_handler->evaluateResponse($this->raw_response[ $module_key ][ $index ]);

        $this->structured_response[ $module_key ][ $index ] = $this->response_handler->getResponseArray();
    }

    /**
     * Appends the status for the given module and index to the comm status
     *
     * If module is a multiple entry, append the counter number if greater than 1
     * NOTE: human readable counters start at 1, so add 1 to the index because
     * that starts at 0
     *
     * @param string $module_key
     * @param integer $index
     */
    protected function appendStatus($module_key, $index)
    {
        if (0 < $index) {

            $count_value = $index + 1;

            $index_separator = ' #' . $count_value;
        } else {

            $index_separator = '';
        }

        $status = $module_key . $index_separator . ' -> ' . $this->structured_response[ $module_key ][ $index ][ 'status' ];

        NF_ZohoCRM()->stored_data->modifyCommData($status, 'comm_status', TRUE);
    }

    /**
     * Appends the errors for the given module and index to the comm errors
     * @param string $module_key
     * @param integer $index
     */
    protected function appendErrors($module_key, $index)
    {
        foreach ($this->structured_response[ $module_key ][ $index ][ 'error_codes' ] as $error_code) {

            NF_ZohoCRM()->stored_data->modifyCommData($error_code, 'errors', TRUE);
        }

        foreach ($this->structured_response[ $module_key ][ $index ][ 'error_messages' ] as $error_messages) {

            NF_ZohoCRM()->stored_data->modifyCommData($error_messages, 'errors', TRUE);
        }
    }

    /**
     * Returns the raw response
     * @param string $module_key Optional key to return specific module
     * @return array
     */
    public function getRawResponse($module_key = '', $index = 0)
    {
        if (0 < strlen($module_key) && isset($this->raw_response[ $module_key ][ $index ])) {

            $return = $this->raw_response[ $module_key ][ $index ];
        } else {

            $return = $this->raw_response;
        }

        return $return;
    }

    /**
     * Returns the structured response
     * @param string $module_key Optional key to return specific module
     * @param integer $index Optional ndex value to return within specific module
     * @return array
     */
    public function getStructuredResponse($module_key = '', $index = 0)
    {
        if (0 < strlen($module_key) && isset($this->structured_response[ $module_key ][ $index ])) {

            $return = $this->structured_response[ $module_key ][ $index ];
        } else {

            $return = $this->structured_response;
        }

        return $return;
    }

}
