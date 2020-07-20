<?php

/**
 * Manipulates the configured FieldMapLookup for more advanced handling
 *
 * @author stuartlb3
 */
class ZohoCRM_FieldMap {

    /**
     * Complete unaltered FieldMapLookup as configured
     * @var array
     */
    protected $configured_list;

    /**
     * Index array of modules
     * @var array
     */
    protected $module_lookup = array(
        'none' => array(),
        'Leads' => array(),
        'Accounts' => array(),
        'Contacts' => array(),
        'Potentials' => array(),
        'Tasks' => array(),
        'Notes' => array(),
        'Quotes'=>array(),
        'Sales_Orders'=>array(),
        'Products'=>array(
            'count'=>3,
        ),
        'QuotesTags'=>array(),
        'SalesOrderTags'=>array(),
        'Parameters' => array(),
    );

    /**
     * Lookup list separated by module for easier handling
     * 
     * This is dynamic and can be modified for multiples or eliminated to 
     * reduce the size of the lookup list.
     *
     * @var array
     */
    protected $module_separated;

    /**
     * Highest count of multiple modules use for looping through multiples
     *
     * @var int
     */
    protected $maximum_iterations = 1;

    /**
     * Field Map array after adjustments
     *
     * @var array
     */
    protected $finalized_field_map;

    /**
     * Configures the FieldMapLookup
     */
    public function __construct() {

        $this->configured_list = NF_ZohoCRM::config( 'FieldMapLookup' );

        $this->modifyFieldMapLookup();

        add_action( 'init', array( $this, 'init' ), 20 );
    }

    /**
     * Runs init functions for filtering
     */
    public function init() {

        // This method controls the list pre-output
    }

    protected function modifyFieldMapLookup() {

        $this->separateIntoModules();

        $this->extractMultiplesAdvancedCommands();

        $this->iterateMultiples();

        $this->removeModules();

        $this->recombineFieldMap();
    }

    /**
     * Group each field map by Module for quicker handling
     */
    protected function separateIntoModules() {

        foreach ( $this->configured_list as $key => $field ) {

            if ( !isset( $field[ 'map_instructions_v2' ] ) ) {

                continue;
            }

            $temp = explode( '.', $field[ 'map_instructions_v2' ] );

            $module = $temp[ 0 ];

            $this->module_separated[ $module ][ $key ] = $field;
        }
    }

    /**
     * Checks if advanced command modifies module count and sets count value
     * 
     * The advanced command to change the module count is {module}_{integer}
     * 
     * The maximum iterations is set based on the overall max to set the upper
     * limit for iterating the loop.
     *
     * 1 is the default, 0 removes from a given module from the list
     */
    protected function extractMultiplesAdvancedCommands() {

        $maximum_iterations = 1;

        foreach ( array_keys( $this->module_lookup ) as $module ) {

            if(!isset($this->module_lookup[ $module ][ 'count' ])){
                $this->module_lookup[ $module ][ 'count' ] = 1;
            }
            
            $search = $module . '_';

            $advanced_command = NF_ZohoCRM()->stored_data->variableAdvancedCommand( $search );

            if ( FALSE !== NF_ZohoCRM()->stored_data->variableAdvancedCommand( $search ) ) {

                $this->module_lookup[ $module ][ 'count' ] = $advanced_command;

                $maximum_iterations = max( $maximum_iterations, $advanced_command );
            }
        }

        $this->maximum_iterations = $maximum_iterations;
    }

    /**
     * Iterates to call method that adds multiple module values to field lookup
     */
    protected function iterateMultiples() {

        foreach ( $this->module_lookup as $module => $module_settings ) {

            if ( 1 >= $module_settings[ 'count' ] ) {

                continue;
            }

            $module_max = $module_settings[ 'count' ];

            $counter = 1;

            $key_field_list = $this->module_separated[ $module ];
            
            while ( $counter < $module_max ) {

                $this->addMultipleModule( $module, $counter, $key_field_list );

                $counter++;
            }
        }
    }

    /**
     * Adds a multiple to a given module
     *
     * Duplicates each field in module, adding a delimiter plus the index
     * number to the key and adding a delimiter to the label plus the index
     * number PLUS ONE (because people default counting from 1)
     *
     * @param string $module Module for adding multiples
     * @param int $counter Index for which multiple to add
     * @param array $key_field_list Array of key-fields for a given module before adjusting for multiples
     */
    protected function addMultipleModule( $module, $counter, $key_field_list ) {

        $delimiter = NF_ZohoCRM()->constants->multiple_module_delimiter;

        $human_counter = $counter + 1;

        foreach ( $key_field_list as $key => $field ) {

            $new_key = $key . $delimiter . $counter;

            $new_field = $field;

            $new_field[ 'label' ] = $field[ 'label' ] . ' ' . $human_counter;

            $this->module_separated[ $module ][ $new_key ] = $new_field;
        }
    }

    /**
     * Checks if advanced command removes module, then removes it
     */
    protected function removeModules() {

        foreach ( $this->module_lookup as $module => $module_settings ) {

            if ( 0 === $module_settings[ 'count' ] ) {

                unset( $this->module_separated[ $module ] );
            }
        }
    }

    /**
     * Recombines separated modules into single field map array
     */
    protected function recombineFieldMap() {

        $finalized = array();

        foreach ( $this->module_separated as $module_fields_array ) {

            $finalized = $finalized + $module_fields_array;
        }

        $this->finalized_field_map = $finalized;
    }

    public function labelKeyPairs(){
        
        $label_key_pairs = array();
        
        foreach($this->finalized_field_map as $key=>$array){
            
            $label_key_pairs[$array['label']] = $key;
        }
        
        return $label_key_pairs;
    }
    
    /**
     * Returns the count of the highest number of modules
     * 
     * Used to set an upper limit on how many times to iterate the field maps
     */
    public function maxModuleCount(){
        
        return $this->maximum_iterations;
    }
    
    /**
     * Returns the full field map lookup as originally configured
     * @return array
     */
    public function fieldMapLookup() {

        return $this->finalized_field_map;
    }

}
