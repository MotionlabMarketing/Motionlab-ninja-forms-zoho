<?php

class ZohoCRM_BuildRequestArray
{

    /**
     * Action data from form submission used to build request array
     * @var array
     */
    protected $field_map_data;

    /**
     * Array of fields in the repeater to be extracted
     *
     * Using a config file to maintain a single location
     *
     * 'form_field'
     * 'field_map'
     * 'entry_type'
     *
     * @var array
     */
    protected $fields_to_extract;

    /**
     * Extracted values for each line of the option repeater
     *
     * A compact version of only the required information;  used to store the
     * entire $action_settings from the form action but that contains a
     * significant amount of unused information that slows down the operation
     * and makes it difficult to read the data during troubleshooting
     *
     * @var array
     */
    protected $field_map_array = array();

    /**
     * The configured lookup array for field maps
     *
     * $key is a READABLE and unique value that is sent with the form submission
     *
     * $label is the i10n descriptive version of the field map, used to help the
     * form designer select the desired field map location
     *
     * $map_instructions is a period-delimited instruction set used to build the
     * array from which the XML is built
     *
     * @var array
     */
    protected $field_map_lookup;

    /**
     * Lookup array of custom validation functions
     * 
     * Array is keyed on a custom callback in curly braces; values are arrays
     * of ValidationClass validation methods
     *  {callback_key}=>array('validationMethod1','validationMethod2')
     * 
     * 
     * @var array
     */
    protected $custom_validation_functions;
    
    /**
     * Authorization level from AuthParam; determines API field map value
     * @var string
     */
    protected $auth_level;

    /**
     * Structured array into which all form data is placed.
     *
     * @var array
     */
    protected $request_array;

    /**
     * Builds request array from the field map data
     *
     * Cycles through the field map data provided by the FieldMapArray class
     * and builds the request array structure needed to construct the formatted
     * request.
     * @param array $field_map_data Field map data extracted from action
     * @param string $auth_level Authorization level from AuthParam
     */
    public function __construct($field_map_data, $auth_level)
    {
        $this->field_map_data = $field_map_data;

        $this->fields_to_extract = NF_ZohoCRM::config('FieldsToExtract');

        $this->field_map_lookup = NF_ZohoCRM()->field_map->fieldMapLookup();

        $this->custom_validation_functions = NF_ZohoCRM::config('CustomValidationFunctions');
        
        $this->auth_level = $auth_level;

        $this->request_array = array(); // initialize

        $this->iterateFormSubmission();
    }

    /**
     * Iterate through each form field submission data
     */
    protected function iterateFormSubmission()
    {

        foreach ($this->field_map_data as $field_data) {// iterate through each mapped field
            $map_args = $this->extractMapArgs($field_data);

            $this->field_map_array[] = $map_args;

            $configured_map_args = $this->retrieveFieldArgs($map_args);

            $validated_map_args = $this->validateField($configured_map_args);

            $this->insertMapArgsIntoRequestArray($validated_map_args);
        }
    }

    /**
     * Extract the map args from a single line of the option repeater
     *
     * Keys for map args are configured from the FieldsToExtract config file
     *
     * @param array $field_data Single field map line from option repeater
     * @return array Map args keyed on config'd Fields To Extract
     */
    protected function extractMapArgs($field_data)
    {

        $map_args = array();

        foreach ($this->fields_to_extract as $field_to_extract) { // iterate through each column in the repeater
            if (isset($field_data[ $field_to_extract ])) {

                $map_args[ $field_to_extract ] = $field_data[ $field_to_extract ];
            } else {

                continue; // if any value isn't set, move on to next field
            }
        }

        return $map_args;
    }

    /**
     * Uses "field_map" key to pull map instructions from the configured FieldMapArray
     *
     * Adds validation functions if configured; default validation_functions is
     * an empty array
     *
     * Additional keys (beyond config'd keys):
     *
     * map_instructions, validation_functions, index
     *
     * @param array $map_args Keyed on config'd Fields To Extract
     * @return array
     */
    protected function retrieveFieldArgs($map_args)
    {

        $parsed_field_map = $this->parseFieldMap($map_args[ 'field_map' ]);

        $field_map_key = $parsed_field_map[ 'field_map_lookup' ];

        $map_args[ 'index' ] = $parsed_field_map[ 'index' ];

        if ($this->auth_level === NF_ZohoCRM()->constants->authlevel_v2) {

            $map_instructions_key = 'map_instructions_v2';
        } else {

            $map_instructions_key = 'map_instructions';
        }

        if (isset($this->field_map_lookup[ $field_map_key ][ $map_instructions_key ])) {

            $map_args[ 'map_instructions' ] = $this->field_map_lookup[ $field_map_key ][ $map_instructions_key ];
        } else {

            $map_args[ 'map_instructions' ] = $map_args[ 'field_map' ];
        }

        $map_args = $this->addValidationFunctions($field_map_key, $map_args);

        return $map_args;
    }

    /**
     * Adds standard and custom-requested validation functions
     * 
     * Uses field map lookup to add standard validation functions for the 
     * standard Zoho field.  Custom validation is added via the custom fields
     * column in the field map.  Custom validation commands are surrounded by
     * curly braces and, when found in the custom field, add the designated 
     * validation function AND remove the curly braces from the custom field to 
     * enable use of the custom field for both custom field map and validation
     * 
     * @param string $field_map_key
     * @param array $map_args
     * @return array
     */
    protected function addValidationFunctions($field_map_key, $map_args)
    {
        if (isset($this->field_map_lookup[ $field_map_key ][ 'validation_functions' ])) {

            $map_args[ 'validation_functions' ] = $this->field_map_lookup[ $field_map_key ][ 'validation_functions' ];
        } else {

            $map_args[ 'validation_functions' ] = array();
        }



        foreach ($this->custom_validation_functions as $search => $validation_functions) {

            if (strpos($map_args[ 'custom_field' ], $search)) {

                // remove the validation command from the custom field map
                $map_args[ 'custom_field' ] = str_replace($search, '', $map_args[ 'custom_field' ]);

                // add the validation function

                if (empty($map_args[ 'validation_functions' ])) {

                    $map_args[ 'validation_functions' ] = $validation_functions;
                } else {
                    $map_args[ 'validation_functions' ] = array_merge($map_args['validation_functions'], $validation_functions);
                }
            }
        }

        return $map_args;
    }

    /**
     * Checks for optional instructions in field map and extracts as separate key
     *
     * Default field_map is key from FieldMapLookup.  TODO: add details when figured out more thoroughly
     *
     * @param string $map_args_field_map Value from field_map key in option repeater
     * @return array Parsed into field_map_lookup, index
     */
    protected function parseFieldMap($map_args_field_map)
    {

        $parsed = array();

        $delimiter = NF_ZohoCRM()->constants->multiple_module_delimiter;

        if (!strpos($map_args_field_map, $delimiter)) {

            $parsed[ 'field_map_lookup' ] = $map_args_field_map;

            $parsed[ 'index' ] = 0;
        } else {

            $parsed[ 'field_map_lookup' ] = strstr($map_args_field_map, $delimiter, TRUE);

            $remove_me = $parsed[ 'field_map_lookup' ] . $delimiter;

            $parsed[ 'index' ] = intval(str_replace($remove_me, '', $map_args_field_map));
        }

        return $parsed;
    }

    /**
     * Modifies the form_field value per validation instructions
     *
     * @param array $map_args Map args for a given option repeater line
     * @return array Modified form_field inside the map args
     */
    protected function validateField($map_args)
    {

        $value_in = $map_args[ 'form_field' ];

        if ($map_args[ 'validation_functions' ]) {

            $temp = $value_in;

            $validation_object = NF_ZohoCRM()->factory->validateFields();

            foreach ($map_args[ 'validation_functions' ] as $function_call) {

                if (!method_exists($validation_object, $function_call)) {

                    continue;
                }
                $temp = call_user_func(array( $validation_object, $function_call ), $temp);
            }

            $value_out = $temp;
        } else {

            $value_out = $value_in;
        }

        $map_args[ 'form_field' ] = $value_out;

        return $map_args;
    }

    /**
     * Inserts the form field in the request array as specified in the configuration
     * @param array $map_args
     */
    protected function insertMapArgsIntoRequestArray($map_args)
    {

        $form_field = $map_args[ 'form_field' ];

        $custom_field = $map_args[ 'custom_field' ];

        $index = $map_args[ 'index' ];

        // explode the instructions into an array of instructions
        $instruction_array = explode('.', $map_args[ 'map_instructions' ]);

        $module = $instruction_array[ 0 ];

        $field_name = $instruction_array[ 1 ];

        // select processing method by element
        switch ($field_name) {

            case 'custom':

                $this->request_array[ $module ][ $index ][ $custom_field ] = $form_field;

                break;
            
            case 'nested':
                $major = $instruction_array[ 2 ];
                
                $minor = $instruction_array[ 3 ];
                
                $this->request_array[ $module ][ $index ][$major][$minor] = $form_field;
                
                break;
                
            default:

                $this->request_array[ $module ][ $index ][ $field_name ] = $form_field;

                break;
        }
    }

    /**
     * Returns the request array as built from the form submission action
     *
     * Initialized as empty array on construct
     *
     * @return array
     */
    public function getRequestArray()
    {

        return $this->request_array;
    }

    /**
     * Returns the field map array as extracted from the form submission action
     *
     * Initialized as empty array on construct
     *
     * @return array
     */
    public function getFieldMapArray()
    {

        return $this->field_map_array;
    }

}
