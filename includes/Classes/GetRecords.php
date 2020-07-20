<?php

class ZohoCRM_GetRecords
{

    /**
     * Comm object authorized for v2
     * @var \ZohoCRM_Comm 
     */
    protected $comm;

    /**
     * Response handling object
     * 
     * @var \ZohoCRM_ResponseHandler
     */
    protected $response_handler;

    public function __construct()
    {
        $this->comm = NF_ZohoCRM()->factory->authorizedV2Comm();

        $this->response_handler = NF_ZohoCRM()->factory->responseHandler();
    }

    /**
     * Retrieves records of a given module
     * 
     * @param string $module_name Module name for which to get records
     * @param integer $number Number of records to retrieve (optional)
     * @return array ResponseArray - see config'd ResponseArray
     */
    public function getModuleRecords($module_name, $number = 1)
    {
        $endpoint = $module_name . '?per_page=' . $number;

        $request_type = 'GET';

        $json = ''; // no JSON body for GET request

        $raw_response = $this->comm->makeCommRequest($request_type, $endpoint, $json);

        $this->response_handler->evaluateGETRecordResponse($raw_response);

        $processed_response = $this->response_handler->getResponseArray();

        return $processed_response;
    }

    /**
     * Returns a single record of a given module
     * 
     * @param string $module_name
     * @return array Array of keyed single record; empty record on fail
     */
    public function getSingleRecord($module_name)
    {
        $endpoint = $module_name;
        
        $request_type = 'GET';

        $json = ''; // no JSON body for GET request

        $raw_response = $this->comm->makeCommRequest($request_type, $endpoint, $json);

        $this->response_handler->evaluateGETRecordResponse($raw_response);

        $processed_response = $this->response_handler->getResponseArray();

        if(isset($processed_response['api_data']['data'][0])){
            
            $return = $processed_response['api_data']['data'][0];
        }else{
            
            $return = array();
        }
        
        return $return;   
    }

    /**
     * Get record by Module name and ID
     * @param string $module_name
     * @param string $id
     * @return array Array of keyed single record; empty record on fail
     */
    public function getRecordByID($module_name,$id){
        
        $endpoint = $module_name.'/'.$id;
        
        $request_type = 'GET';

        $json = ''; // no JSON body for GET request

        $raw_response = $this->comm->makeCommRequest($request_type, $endpoint, $json);

        $this->response_handler->evaluateGETRecordResponse($raw_response);

        $processed_response = $this->response_handler->getResponseArray();

        if(isset($processed_response['api_data']['data'][0])){
            
            $return = $processed_response['api_data']['data'][0];
        }else{
            
            $return = array();
        }
        
        return $return; 
    }
}
