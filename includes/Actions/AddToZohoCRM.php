<?php

if ( !defined( 'ABSPATH' ) || !class_exists( 'NF_Abstracts_Action' ) )
    exit;

/**
 * 
 */
final class NF_ZohoCRM_Actions_AddToZohoCRM extends NF_Abstracts_Action {

    protected $_name = 'addtozohocrm'; // child CRM
    protected $_tags = array();
    protected $_timing = 'normal';
    protected $_priority = '10';

    /**
     * The field data from the form submission needed for building the field map array
     * @var array
     */
    protected $fields_to_extract;

    /**
     * The array of mapping data to be built into the request object
     *
     * '    form_field'=>'form_field' ,
     *      'map_args' =>{
     *          'field_map'=>,
     *          'entry_type'=>,
     *          'custom_field_map'=>,
     *      }
     * @var array
     */
    protected $field_map_array;

    /**
     * The lookup array to convert readable field map to programmtic field map
     * @var array
     */
    protected $field_map_lookup;

    public function __construct() {
        parent::__construct();

        $this->_nicename = __( 'Add To Zoho CRM', 'ninja-forms' );

        $this->fields_to_extract = NF_ZohoCRM::config( 'FieldsToExtract' );

        // initialize the action settings
        add_action( 'admin_init', array( $this, 'initializeSettings' ) );

        // create the output template
        add_action( 'ninja_forms_builder_templates', array( $this, 'builderTemplates' ) );
    }

    /*
     * PUBLIC FUNCTIONS
     *
     */

    public function save( $action_settings ) {

        return $action_settings;
    }

    /**
     * Process the submitted form
     * @param array $action_settings
     * @param type $form_id
     * @param array $data
     */
    public function process( $action_settings, $form_id, $data ) {

        $process = NF_ZohoCRM()->factory->process( $action_settings, $form_id, $data );

        $process->processAction();

        $after_data = $process->getData();

        return $after_data;
    }

    public function builderTemplates() {

        NF_ZohoCRM::template( 'custom-field-map-row.html' );

        //TODO: develop method to use alternate template
        NF_ZohoCRM::template( 'custom-field-map-row-alt.html' );
    }

    /**
     * Initialize action settings using config settings and constructed options
     */
    public function initializeSettings() {

        // bring in configured settings
        $configured_settings = NF_ZohoCRM::config( 'ActionFieldMapSettings' );

        $settings = $this->setFieldMapOptions( $configured_settings );
//        $settings = $this->useAlternateFieldMapTemplate($configured_settings);

        $this->_settings = array_merge( $this->_settings, $settings );
    }

    /**
     * Adds the field map dropdown options
     *
     * @param array $settings Configured action settings
     * @return array
     */
    protected function setFieldMapOptions( $settings ) {

        // set dropdown for fields from config file

        $settings[ NF_ZohoCRM()->constants->field_map_action_key ][ 'columns' ][ 'field_map' ][ 'options' ] = $this->buildFieldmapDropdown( );

        return $settings;
    }

    /**
     * Switches to alt field map template with text box instead of option dropdown
     * @param array $settings Configured action settings
     * @return array
     */
    protected function useAlternateFieldMapTemplate( $settings ) {

        $settings[ NF_ZohoCRM()->constants->field_map_action_key ][ 'tmpl_row' ] = 'nf-tmpl-zoho-custom-field-map-row-alt';

        return $settings;
    }

    /**
     * Build the array of each field to be sent
     *
     * @return array
     */
    protected function buildFieldmapDropdown( ) {

        $dropdown_array = array();

                
        $this->field_map_lookup = NF_ZohoCRM()->field_map->fieldMapLookup();
        
        foreach ( $this->field_map_lookup as $key => $label_map_array ) {

            $dropdown_array[] = array(
                'label' => $label_map_array[ 'label' ],
                'value' => $key,
            );
        }

        return $dropdown_array;
    }

}
