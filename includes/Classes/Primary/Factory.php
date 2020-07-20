<?php

/**
 * Factory providing instantiated objects
 *
 * @author stuartlb3
 *
 */
class ZohoCRM_Factory
{

    /**
     * Authorization level, possible decision factor; public property can be set externally
     * @var string
     */
    public $authlevel = '';

    /**
     * Returns a Process Object
     *
     * Process Object is instantiated by the process method of the Action
     * @param array $action_settings Action settings of fired action
     * @param integer $form_id Form id of fired action
     * @param array $data Data from fired action
     * @return ZohoCRM_Process
     */
    public function process($action_settings, $form_id, $data)
    {

        $choice = 'default';

        if (NF_ZohoCRM()->stored_data->isAdvancedCommandSet('use_310_process')) {

            $choice = 'use_310';
        }

        switch ($choice) {

            case 'use_310':
                NF_ZohoCRM::file_include('Classes', 'Process_310');
                $process_object = new ZohoCRM_Process310($action_settings, $form_id, $data);
                break;

            default:
                NF_ZohoCRM::file_include('Classes', 'Process');
                $process_object = new ZohoCRM_Process($action_settings, $form_id, $data);
                break;
        }

        return $process_object;
    }

    /**
     * Returns a CommDataMarkup object
     * @return \ZohoCRM_CommDataMarkup
     */
    public function commDataMarkup()
    {

        NF_ZohoCRM::file_include('Classes', 'CommDataMarkup');

        return new ZohoCRM_CommDataMarkup( );
    }

    /**
     * Returns an AccountDataMarkup object
     * @return \ZohoCRM_AccountDataMarkup
     */
    public function accountDataMarkup()
    {

        NF_ZohoCRM::file_include('Classes', 'AccountDataMarkup');

        return new ZohoCRM_AccountDataMarkup( );
    }

    /**
     * Returns a SupportMarkup object
     * @return \ZohoCRM_SupportMarkup
     */
    public function supportMarkup()
    {

        NF_ZohoCRM::file_include('Classes', 'SupportMarkup');

        return new ZohoCRM_SupportMarkup();
    }

    /**
     * Returns an AuthParams Object
     * @return \ZohoCRM_AuthParams
     */
    public function authParams()
    {

        NF_ZohoCRM::file_include('Classes', 'AuthParams');

        return new ZohoCRM_AuthParams();
    }

    /**
     * Returns a BuildRequestArray object
     * @param array $field_map_data Field map data from action
     * @param string $auth_level Authlevel from AuthParam
     * @return \ZohoCRM_BuildRequestArray
     */
    public function buildRequestArray($field_map_data, $auth_level)
    {

        NF_ZohoCRM::file_include('Classes', 'BuildRequestArray');

        return new ZohoCRM_BuildRequestArray($field_map_data, $auth_level);
    }

    /**
     * Returns a ValidateFields object
     * @return \ZohoCRM_ValidateFields
     */
    public function validateFields()
    {

        NF_ZohoCRM::file_include('Classes', 'ValidateFields');

        return new ZohoCRM_ValidateFields();
    }

    /**
     * Returns a Communication Object
     * @param type $credentials
     * @return \ZohoCommObject
     */
    public function commObject($credentials)
    {

        if (isset($credentials[ 'nfzohocrm_authtoken' ])) {

            $authtoken = $credentials[ 'nfzohocrm_authtoken' ];
        } else {
            // this should not be necessary; kept in case I made an error
            $authtoken = $credentials[ 'authtoken' ];
        }

        switch ($this->authlevel) {

            case( NF_ZohoCRM()->constants->authlevel_none):
            case (NF_ZohoCRM()->constants->authlevel_v1):

                if (class_exists('ZohoCommObjectPlus')) {

                    $zohocomm = new ZohoCommObjectPlus($authtoken);
                } else {

                    $zohocomm = new ZohoCommObject($authtoken);
                }
                break;

            case (NF_ZohoCRM()->constants->authlevel_v2):
            default:

                NF_ZohoCRM::file_include('Classes', 'Comm');

                $zohocomm = new ZohoCRM_Comm($credentials);
        }

        return $zohocomm;
    }

    /**
     * Returns an Oauth Token object
     * @return ZohoCRM_Tokens
     */
    public function oauthTokens($credentials)
    {

        NF_ZohoCRM::file_include('Classes', 'Tokens');

        return new ZohoCRM_Tokens($credentials);
    }

    /**
     * Returns a RequestManager object
     * @return \ZohoCRM_RequestManager
     * @param array $request_array Request array
     * @param ZohoCRM_Comm $comm_object Communication object
     */
    public function requestManager($request_array, $comm_object)
    {

        NF_ZohoCRM::file_include('Classes', 'RequestManager');

        return new ZohoCRM_RequestManager($request_array, $comm_object);
    }

    /**
     * Returns a ResponseHandler object
     * @return \ZohoCRM_ResponseHandler
     */
    public function responseHandler()
    {
        NF_ZohoCRM::file_include('Classes', 'ResponseHandler');

        return new ZohoCRM_ResponseHandler();
    }

    /**
     * Returns new getRecords object
     * @return \ZohoCRM_GetRecords
     */
    public function getRecords()
    {
        NF_ZohoCRM::file_include('Classes', 'GetRecords');

        return new ZohoCRM_GetRecords();
    }

    /**
     * Checks for valid v2 credentials and returns credentialed comm object
     *
     * Returns false if not authorized for v2
     * @return \ZohoCRM_Comm
     */
    public function authorizedV2Comm()
    {
        $return = false;

        $auth_params = $this->authParams();

        $auth_level = $auth_params->authLevel();

        $is_authorized = $auth_params->isAuthorized();

        if ($is_authorized && NF_ZohoCRM()->constants->authlevel_v2 === $auth_level) {

            $credentials = $auth_params->credentials();

            $return = $this->commObject($credentials);
        }

        return $return;
    }

}
