<?php

/**
 * Mark up the Settings Page HTML for screen output
 *
 * @author stuartlb3
 */
class ZohoCRM_CommDataMarkup {

    /**
     * Communication data for display on settings page
     * @var array
     */
    protected $comm_data;

    /**
     * Version of the data structure; used to format the settings output
     *
     * '310' is for version 3.1.0 and earlier
     * '320' is the structure released on 3.2.0 and later
     * @var string
     */
    protected $settings_version;

    /**
     * Marked up comm data ready for HTML display
     *
     * Values are strings as HTML markup
     * @var array
     */
    protected $marked_up_comm_data = array(
        'comm_status' => '',
        'errors' => '',
        'field_map_array' => '',
        'request_array' => '',
        'structured_response' => '',
        'raw_response' => '',
    );

    /**
     * Given comm data array, marks up HTML for settings page
     * @param array $comm_data Communication data for display on settings page
     */
    public function __construct() {

        $this->comm_data = NF_ZohoCRM()->stored_data->commData();

        if ( !isset( $this->comm_data[ 'settings_version' ] ) ||
                'unknown' === $this->comm_data[ 'settings_version' ] ) {

            $this->settings_version = '310';

            $this->markup310();
        } else {

            $this->settings_version = '320';

            $this->markup();
        }
    }

    /**
     * Call methods to mark up the current version data structure
     */
    protected function markup() {

        $this->commStatus();

        $this->fieldMapArray();

        $this->requestArray();

        $this->structuredResponse();

        $this->rawResponse();

        $this->errors();
    }

    /**
     * Call methods for marking up v3.1.0 and earlier
     */
    protected function markup310() {

        $this->commStatus310();

        $this->fieldMapArray310();

        $this->requestArray310();

        $this->structuredResponse310();

        $this->rawResponse();

        $this->errors310();
    }

    protected function commStatus() {

        if ( $this->isIterable( 'comm_status') ) {

            $markup = implode('<br />',$this->comm_data[ 'comm_status' ]);
        } else {

            $markup = __( 'No Comm Status currently available', 'ninja-forms-zoho-crm' );
        }

        $this->marked_up_comm_data[ 'comm_status' ] = $markup;
    }

    /**
     * Mark up the field map array
     */
    protected function fieldMapArray() {

        $markup = __( 'No field map array currently available', 'ninja-forms-zoho-crm' ); // default

        if ( $this->isIterable( 'field_map_array' ) ) {


            $markup = '<table>'; // re-initialize
            $markup .= '<tr><th>Form Field</th><th>Field Map</th><th>Custom Field</th></tr>';

            foreach ( $this->comm_data[ 'field_map_array' ] as $single_field_array ) {
                $markup .= '<tr><td>' . $single_field_array[ 'form_field' ] . '</td><td>' . $single_field_array[ 'field_map' ] . '</td><td>' . $single_field_array[ 'custom_field' ] . '</td></tr>';
            }
            $markup .= '</table>';
        }

        $this->marked_up_comm_data[ 'field_map_array' ] = $markup;
    }

    /**
     * Add html markup to request array for Settings page display
     *
     * TODO: clean up this method, possibly separating loops
     */
    protected function requestArray() {

        if ( !$this->isIterable( 'request_array' ) ) {

            $markup = __( 'No request information available', 'ninja-forms-zoho-crm' );

            $this->marked_up_comm_data[ 'request_array' ] = $markup;

            return;
        }

        $markup = '<table>';

        $markup .= '<tr><th>Module</th><th>Zoho Field</th><th>Value</th></tr>';

        foreach ( $this->comm_data[ 'request_array' ] as $module => $module_array ) {

            foreach ( $module_array as $index => $module_array ) {
                if(!is_array($module_array)){ continue;} // added 2018.06.13 TODO: add details
                foreach ( $module_array as $key => $value ) {

                    // Added maybe_serialize as part of adding multi-picklist
                    $markup .= '<tr><td>' . $module . '_' . $index . '</td><td>' . $key . '</td><td>' . maybe_serialize($value) . '</td></tr>';
                }
            }
        }

        $markup .= '</table>';

        $this->marked_up_comm_data[ 'request_array' ] = $markup;
    }

    /**
     * Build table for each module and response
     */
    protected function structuredResponse() {

        $markup = __( 'No structured response information available', 'ninja-forms-zoho-crm' ); // default

        if ( $this->isIterable( 'structured_response' ) ) {

            $markup = '<table>'; // re-initialize

            foreach ( $this->comm_data[ 'structured_response' ] as $module => $response ) {
                $markup .= '<tr><td>' . $module . '</td><td> ' . serialize( $response ) . '</td></tr>';
            }
            $markup .= '</table>';
        }

        $this->marked_up_comm_data[ 'structured_response' ] = $markup;
    }

    /**
     * Build mark up for errors
     *
     * Implode indexes array of string elements
     */
    protected function errors() {

        $markup = ''; // default

        if ( $this->isIterable( 'errors' ) ) {

            $markup = implode('<br />', $this->comm_data[ 'errors' ] );
        }

        $this->marked_up_comm_data[ 'errors' ] = $markup;
    }

    protected function rawResponse() {

        $markup = __( 'No raw response available', 'ninja-forms-zoho-crm' ); // default

        if ( $this->isIterable( 'response' ) ) {

            $markup = '<table>'; // re-initialize

            foreach ( $this->comm_data[ 'response' ] as $module => $response ) {
                $markup .= '<tr><td>' . $module . '</td><td> ' . serialize( $response ) . '</td></tr>';
            }
            $markup .= '</table>';
        }

        $this->marked_up_comm_data[ 'raw_response' ] = $markup;
    }

    protected function commStatus310() {

        if ( isset( $this->comm_data[ 'zoho_comm_status' ] ) ) {

            $markup = $this->comm_data[ 'zoho_comm_status' ];
        } else {

            $markup = __( 'No Comm Status currently available', 'ninja-forms-zoho-crm' );
        }

        $this->marked_up_comm_data[ 'comm_status' ] = $markup;
    }

    /**
     * Mark up the field map array for v3.1.0 and before
     */
    protected function fieldMapArray310() {

        $markup = __( 'No field map array currently available', 'ninja-forms-zoho-crm' ); // default

        if ( $this->isIterable( 'field_map_array' ) ) {


            $markup = '<table>'; // re-initialize
            $markup .= '<tr><th>Form Field</th><th>Field Map</th><th>Custom Field</th></tr>';

            foreach ( $this->comm_data[ 'field_map_array' ] as $single_field_array ) {
                $markup .= '<tr><td>' . $single_field_array[ 'form_field' ] . '</td><td>' . $single_field_array[ 'field_map' ] . '</td><td>' . $single_field_array[ 'custom_field' ] . '</td></tr>';
            }
            $markup .= '</table>';
        }

        $this->marked_up_comm_data[ 'field_map_array' ] = $markup;
    }

    /**
     * Add HTML markup to request array on Settings page, v3.1.0 and earlier
     */
    protected function requestArray310() {

        $markup = __( 'No request information available', 'ninja-forms-zoho-crm' ); // default

        if ( $this->isIterable( 'request_array' ) ) {

            $markup = '<table>'; // re-initialize
            $markup .= '<tr><th>Module</th><th>Zoho Field</th><th>Value</th></tr>';

            foreach ( $this->comm_data[ 'request_array' ] as $single_field_array ) {
                $markup .= '<tr><td>' . $single_field_array[ 'module' ] . '</td><td>' . $single_field_array[ 'field_map' ] . '</td><td>' . $single_field_array[ 'form_field' ] . '</td></tr>';
            }
            $markup .= '</table>';
        }

        $this->marked_up_comm_data[ 'request_array' ] = $markup;
    }

    /**
     * Build table for each module and response
     */
    protected function structuredResponse310() {

        $markup = __( 'No structured response information available', 'ninja-forms-zoho-crm' ); // default

        if ( $this->isIterable( 'structured_response' ) ) {

            $markup = '<table>'; // re-initialize

            foreach ( $this->comm_data[ 'structured_response' ] as $module => $response ) {
                $markup .= '<tr><td>' . $module . '</td><td> ' . serialize( $response ) . '</td></tr>';
            }
            $markup .= '</table>';
        }

        $this->marked_up_comm_data[ 'structured_response' ] = $markup;
    }

    /**
     * Build mark up for errors, version 3.1.0 and earlier
     */
    protected function errors310() {

        $markup = ''; // default

        if ( $this->isIterable( 'errors' ) ) {

            $markup = serialize( $this->comm_data[ 'errors' ] );
        }

        $this->marked_up_comm_data[ 'errors' ] = $markup;
    }

    /**
     * Is the comm data for the given key an array with values to iterate
     *
     * Is it set, is it an array, and is it not empty
     *
     * @param string $key Comm data key to check
     */
    protected function isIterable( $key ) {

        $evaluation = FALSE;
        if ( isset( $this->comm_data[ $key ] ) &&
                is_array( $this->comm_data[ $key ] ) &&
                !empty( $this->comm_data[ $key ] ) ) {

            $evaluation = TRUE;
        }
        return $evaluation;
    }

    /**
     * Returns the marked data for settings, keyed array of string markups
     * @return array
     */
    public function getMarkup() {

        return $this->marked_up_comm_data;
    }

}
