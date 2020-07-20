<?php

/**
 * Mark up the Settings Page HTML for screen output
 *
 * @author stuartlb3
 */
class ZohoCRM_AccountDataMarkup {

    /**
     * Field map data for display on settings page
     * @var array
     */
    protected $field_map_data;

    /**
     * Version of the data structure; used to format the settings output
     *
     * '310' is for version 3.1.0 and earlier
     * '320' is the structure released on 3.2.0 and later
     * @var string
     */
    protected $settings_version;

    /**
     * Marked up account data ready for HTML display
     *
     * Values are strings as HTML markup
     * @var array
     */
    protected $marked_up_account_data = array(
        'manual_field_map' => '',
    );

    /**
     * Marks up HTML for settings page
     * @param array $comm_data Communication data for display on settings page
     */
    public function __construct() {

            $this->settings_version = '320';

            $this->field_map_data = NF_ZohoCRM()->field_map->labelKeyPairs();
            
            $this->markup();
        
    }

    /**
     * Call methods to mark up the current version data structure
     */
    protected function markup() {

        $this->manualFieldMap();

    }


    protected function manualFieldMap() {

        $markup = '<table>';
        
        foreach( $this->field_map_data as $label =>$key){
            
            $markup .= '<tr><td>'.$label.'</td><td>'.$key.'</td></tr>';
        }
        
        $markup .= '</table>';
        
        $this->marked_up_account_data[ NF_ZohoCRM()->constants->manual_field_map] = $markup;
    }

 

    /**
     * Returns the marked data for settings, keyed array of string markups
     * @return array
     */
    public function getMarkup() {

        return $this->marked_up_account_data;
    }

}
