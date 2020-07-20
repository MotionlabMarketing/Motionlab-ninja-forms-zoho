<?php

/**
 * New to 3.0, the request object extracts data from the submission action
 * 
 * Iterating through the option repeater and any other action settings,
 * it builds the request array for the Comm Object to process
 */
class ZohoRequestObject {

    /**
     *  Field map data array
     * 
     * This is pulled in from the $action_settings[ {key} ] and be iterated
     * to extract the form submission and the field mapping instructions
     * 
     * @var array
     */
    protected $field_map_data;

    /**
     * Array of submission field names for which to extract data
     * 
     * Co-ordinated with the ActionFieldMap and the FieldsToExtract configs
     * 
     * @var array
     */
    protected $fields_to_extract;

    /**
     * Array of each field of submitted data with mapping instructions
     * 
     * @var array
     */
    protected $request_array;

    /**
     * Array to exchange each field map with detailed mapping instructions
     * 
     * $key is a READABLE and unique value that is sent with the form submission
     * 
     * $label is the i10n descriptive version of the field map, used to help the
     * form designer select the desired field map location
     * 
     * $map_instructions is a detailed mapping instructions; can be built as
     * an imploded array
     */
    protected $field_map_lookup;

    public function __construct( $field_map_data ) {

        $this->field_map_data = $field_map_data;

        $this->fields_to_extract = NF_ZohoCRM::config( 'FieldsToExtract' );

        $this->field_map_lookup = NF_ZohoCRM()->field_map->fieldMapLookup();

        $this->request_array = array();  // initialize
        
        $this->iterate_field_map_data();
    }

    /**
     * Iterates each inidividual entry in the field map array to build the
     * request array.  Request array is nested array; first level is indexed
     * and second level is associative with each field to extract
     */
    protected function iterate_field_map_data() {
 
        foreach ( $this->field_map_data as $unprocessed_single_field_map ) {

            $single_field_map = array();

            foreach ( $this->fields_to_extract as $field ) {

                if ( isset( $unprocessed_single_field_map[ $field] ) ) {

                    $single_field_map[ $field] = $unprocessed_single_field_map[ $field ];
                }
            }

            // if no form submission value, continue to next field
            if ( !(0 < strlen( $single_field_map[ 'form_field' ] )) ) {

                continue;
            }

            $this->request_array[] = $this->process_single_field_map( $single_field_map );
        }
    }

    protected function process_single_field_map( $single_field_map ) {

        $readable_field_map = $single_field_map[ 'field_map' ];

        $map_instructions = $this->field_map_lookup[ $readable_field_map ][ 'map_instructions' ];

        $exploded_instructions = explode( '.', $map_instructions );

        /*
         * Set module and field map based on exploded instructions
         */

            $single_field_map[ 'field_map' ] = $exploded_instructions[ 1 ];
            $single_field_map[ 'module' ] = $exploded_instructions[ 0 ];


        /*
         * Set field_map to the custom_field if the custom_field has a value
         */
        if ( 0 < strlen( $single_field_map[ 'custom_field' ] ) ) {

            $single_field_map[ 'field_map' ] = $single_field_map[ 'custom_field' ];
        }

        /*
         * Format Annual Revenue to an integer with no currency or separators
         */
        if ( 'Annual Revenue' == $single_field_map[ 'field_map' ] ) {

            $single_field_map[ 'form_field' ] = intval( preg_replace( '/[^0-9.]*/', "", $single_field_map[ 'form_field' ] ) );
        }

        /*
         * Convert to Zoho Boolean format
         * Currently only used for 'checked' 'unchecked' options
         */
        $bool_lookup = NF_ZohoCRM::config( 'ZohoBoolLookup' );

        if ( in_array( $single_field_map[ 'form_field' ], array_keys($bool_lookup) ) ) {

            $single_field_map[ 'form_field' ] = $bool_lookup[ $single_field_map[ 'form_field' ] ];
        }
        
        if ( is_array( $single_field_map[ 'form_field' ] ) ) { //multiple select is being used so convert it to a comma-delineated string
            
            $single_field_map[ 'form_field' ] = esc_attr( implode( ",", $single_field_map[ 'form_field' ] ) );
        }
        
        return $single_field_map;
    }

    /**
     * Request array for building
     * @return array
     */
    public function get_request_array() {

        return $this->request_array;
    }
}
