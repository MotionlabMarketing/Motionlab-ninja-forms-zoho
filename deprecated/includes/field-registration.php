<?php

/* --- NINJA FORMS ZOHO CRM INTEGRATION --- */


/* ----------
  Register the Zoho Field Mapping Option for All Mappable Fields
  ------------------------------------------------------------------------------------------------------------ */

add_action( 'ninja_forms_edit_field_after_registered', 'nfzohocrm_modify_field_defaults', 10 );

function nfzohocrm_modify_field_defaults( $field_id ) {

    // Get the field type for the given field
    $field = ninja_forms_get_field_by_id( $field_id );
    $field_type = $field[ 'type' ];

    // Add standard NF fields that will be mapped
    // Special field mapping can be defined in nfzohocrm_return_field_map_options and assigned here with a string name
    $mappable_nf_fields_array = nfzohocrm_build_mappable_nf_fields_array();


    // Build an array of custom fields pulled from other extensions
    $extension_fields_array = nfzohocrm_grab_custom_fields_from_extensions();

    //Combine the two arrays into one master array of mappable fields
    //The two are kept separate so that an error in an extension doesn't mess with the default behavior
    $extended_mappable_fields_array = array_merge( $mappable_nf_fields_array, $extension_fields_array );


    if ( isset( $extended_mappable_fields_array[ $field_type ] ) ) {

        // pull the field map options for this field type and build the selection list
        $field_map_options = nfzohocrm_return_field_map_options( $extended_mappable_fields_array[ $field_type ] );

        foreach ( $field_map_options as $field_map => $map_code ) {

            $field_map_options_array[] = array( 'name' => $field_map, 'value' => $map_code );
        }

        // set variables for mapping field select option
        $type = 'select';
        $label = __( 'Zoho CRM Field Map', 'ninja-forms-zoho-crm' );
        $name = 'nfzohocrm_field_map';
        $width = 'thin';
        $options = $field_map_options_array;
        $class = 'widefat';
        $desc = ''; //__( 'Map this field to your Zoho CRM', 'ninja-forms-zoho-crm' );
        $label_class = '';


        // check if this field has a field map already selected
        if ( isset( $field[ 'data' ][ 'nfzohocrm_field_map' ] ) ) {
            $value = $field[ 'data' ][ 'nfzohocrm_field_map' ];

            if ( 'divider' == $value ) {

                $value = 'none';
            }
        } else {

            $value = 'none';
        }//end is set
        // Add field mapping option to the field
        ninja_forms_edit_field_el_output(
                $field_id
                , $type
                , $label
                , $name
                , $value
                , $width
                , $options
                , $class
                , $desc
                , $label_class
        );


        // Create the Custom Field Map Box ---*/
        // set variables for mapping field textbox option
        $type = 'text';
        $label = __( 'Custom Zoho Field Map', 'ninja-forms-zoho-crm' );
        $name = 'nfzohocrm_custom_field_map';
        $width = 'thin';
        $options = '';
        $class = 'widefat';
        $desc = '';


        //check if this field has a field map already selected
        if ( isset( $field[ 'data' ][ 'nfzohocrm_custom_field_map' ] ) ) {

            $value = $field[ 'data' ][ 'nfzohocrm_custom_field_map' ];
        } else {

            $value = '';
        }//end is set
        // Add custom field map text box to this field
        ninja_forms_edit_field_el_output(
                $field_id
                , $type
                , $label
                , $name
                , $value
                , $width
                , $options
                , $class
                , $desc
                , $label_class
        );
    } //end isset( $extended_mappable_fields_array[ $field_type ] )
}

// Create field map options
// Default to full Zoho list, custom lists are named by a string passed as $map
function nfzohocrm_return_field_map_options( $map = '' ) {

    $complete_field_map_options = array(
        __( '- None', 'ninja-forms-zoho-crm' ) => 'none',
        __( 'Lead First Name', 'ninja-forms-zoho-crm' ) => 'First Name',
        __( 'Lead Last Name', 'ninja-forms-zoho-crm' ) => 'Last Name',
        __( 'Lead Email', 'ninja-forms-zoho-crm' ) => 'Email',
        __( 'Lead Phone', 'ninja-forms-zoho-crm' ) => 'Phone',
        __( 'Lead Street', 'ninja-forms-zoho-crm' ) => 'Street',
        __( 'Lead City', 'ninja-forms-zoho-crm' ) => 'City',
        __( 'Lead State', 'ninja-forms-zoho-crm' ) => 'State',
        __( 'Lead Zip Code', 'ninja-forms-zoho-crm' ) => 'Zip Code',
        __( 'Lead Country', 'ninja-forms-zoho-crm' ) => 'Country',
        __( '-- company info --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Lead Company', 'ninja-forms-zoho-crm' ) => 'Company',
        __( 'Lead Title', 'ninja-forms-zoho-crm' ) => 'Title',
        __( 'Lead Website', 'ninja-forms-zoho-crm' ) => 'Website',
        __( 'Lead Industry', 'ninja-forms-zoho-crm' ) => 'Industry',
        __( 'Lead Annual Revenue', 'ninja-forms-zoho-crm' ) => 'Annual Revenue',
        __( '-- misc contact info --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Lead Mobile', 'ninja-forms-zoho-crm' ) => 'Mobile',
        __( 'Lead Fax', 'ninja-forms-zoho-crm' ) => 'Fax',
        __( 'Lead Secondary Email', 'ninja-forms-zoho-crmohocrm' ) => 'Secondary Email',
        __( 'Lead Skype ID', 'ninja-forms-zoho-crm' ) => 'Skype ID',
        __( 'Lead Salutation', 'ninja-forms-zoho-crm' ) => 'Salutation',
        __( '-- internal marketing data --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Lead Source', 'ninja-forms-zoho-crm' ) => 'Lead Source',
        __( 'Lead Campaign Source', 'ninja-forms-zoho-crm' ) => 'Campaign Source',
        __( 'Lead Owner', 'ninja-forms-zoho-crm' ) => 'Lead Owner',
        __( 'Lead Status', 'ninja-forms-zoho-crm' ) => 'Lead Status',
        __( 'Lead Rating', 'ninja-forms-zoho-crm' ) => 'Rating',
        __( 'Lead Custom ->', 'ninja-forms-zoho-crm' ) => 'custom',
        __( '-- Account Mapping --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Account Name', 'ninja-forms-zoho-crm' ) => 'Accounts.Account Name',
        __( 'Account Owner', 'ninja-forms-zoho-crm' ) => 'Accounts.Account Owner',
        __( 'Account Website', 'ninja-forms-zoho-crm' ) => 'Accounts.Website',
        __( 'Account Ticker Symbol', 'ninja-forms-zoho-crm' ) => 'Accounts.Ticker Symbol',
        __( 'Account Parent Account', 'ninja-forms-zoho-crm' ) => 'Accounts.Parent Account',
        __( 'Account Employees', 'ninja-forms-zoho-crm' ) => 'Accounts.Employees',
        __( 'Account Ownership', 'ninja-forms-zoho-crm' ) => 'Accounts.Ownership',
        __( 'Account Industry', 'ninja-forms-zoho-crm' ) => 'Accounts.Industry',
        __( 'Account Type', 'ninja-forms-zoho-crm' ) => 'Accounts.Account Type',
        __( 'Account Number', 'ninja-forms-zoho-crm' ) => 'Accounts.Account Number',
        __( 'Account Site', 'ninja-forms-zoho-crm' ) => 'Accounts.Account Site',
        __( 'Account Phone', 'ninja-forms-zoho-crm' ) => 'Accounts.Phone',
        __( 'Account Fax', 'ninja-forms-zoho-crm' ) => 'Accounts.Fax',
        __( 'Account E-mail', 'ninja-forms-zoho-crm' ) => 'Accounts.E-mail',
        __( 'Account Billing Street', 'ninja-forms-zoho-crm' ) => 'Accounts.Billing Street',
        __( 'Account Billing City', 'ninja-forms-zoho-crm' ) => 'Accounts.Billing City',
        __( 'Account Billing State', 'ninja-forms-zoho-crm' ) => 'Accounts.Billing State',
        __( 'Account Billing Code', 'ninja-forms-zoho-crm' ) => 'Accounts.Billing Code',
        __( 'Account Billing Country', 'ninja-forms-zoho-crm' ) => 'Accounts.Billing Country',
        __( 'Account Shipping Street', 'ninja-forms-zoho-crm' ) => 'Accounts.Shipping Street',
        __( 'Account Shipping City', 'ninja-forms-zoho-crm' ) => 'Accounts.Shipping City',
        __( 'Account Shipping State', 'ninja-forms-zoho-crm' ) => 'Accounts.Shipping State',
        __( 'Account Shipping Code', 'ninja-forms-zoho-crm' ) => 'Accounts.Shipping Code',
        __( 'Account Shipping Country', 'ninja-forms-zoho-crm' ) => 'Accounts.Shipping Country',
        __( 'Account Rating', 'ninja-forms-zoho-crm' ) => 'Accounts.Rating',
        __( 'Account SIC Code', 'ninja-forms-zoho-crm' ) => 'Accounts.SIC Code',
        __( 'Account Annual Revenue', 'ninja-forms-zoho-crm' ) => 'Accounts.Annual Revenue',
        __( 'Account Description', 'ninja-forms-zoho-crm' ) => 'Accounts.Description',
        __( 'Account Custom ->', 'ninja-forms-zoho-crm' ) => 'Accounts.custom',
        __( '-- Contact Mapping --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Contact Salutation', 'ninja-forms-zoho-crm' ) => 'Contacts.Salutation',
        __( 'Contact First Name', 'ninja-forms-zoho-crm' ) => 'Contacts.First Name',
        __( 'Contact Last Name', 'ninja-forms-zoho-crm' ) => 'Contacts.Last Name',
        __( 'Contact Owner', 'ninja-forms-zoho-crm' ) => 'Contacts.Contact Owner',
        __( 'Contact Campaign Source', 'ninja-forms-zoho-crm' ) => 'Contacts.Campaign Source',
        __( 'Contact Lead Source', 'ninja-forms-zoho-crm' ) => 'Contacts.Lead Source',
        __( 'Contact Title', 'ninja-forms-zoho-crm' ) => 'Contacts.Title',
        __( 'Contact Department', 'ninja-forms-zoho-crm' ) => 'Contacts.Department',
        __( 'Contact Date of Birth', 'ninja-forms-zoho-crm' ) => 'Contacts.Date of Birth',
        __( 'Contact Description', 'ninja-forms-zoho-crm' ) => 'Contacts.Description',
        __( 'Contact Email', 'ninja-forms-zoho-crm' ) => 'Contacts.Email',
        __( 'Contact Secondary Email', 'ninja-forms-zoho-crm' ) => 'Contacts.Secondary Email',
        __( 'Contact Home Phone', 'ninja-forms-zoho-crm' ) => 'Contacts.Home Phone',
        __( 'Contact Other Phone', 'ninja-forms-zoho-crm' ) => 'Contacts.Other Phone',
        __( 'Contact Skype ID', 'ninja-forms-zoho-crm' ) => 'Contacts.Skype ID',
        __( 'Contact Phone', 'ninja-forms-zoho-crm' ) => 'Contacts.Phone',
        __( 'Contact Mobile', 'ninja-forms-zoho-crm' ) => 'Contacts.Mobile',
        __( 'Contact Fax', 'ninja-forms-zoho-crm' ) => 'Contacts.Fax',
        __( 'Contact Mailing Street', 'ninja-forms-zoho-crm' ) => 'Contacts.Mailing Street',
        __( 'Contact Mailing City', 'ninja-forms-zoho-crm' ) => 'Contacts.Mailing City',
        __( 'Contact Mailing State', 'ninja-forms-zoho-crm' ) => 'Contacts.Mailing State',
        __( 'Contact Mailing Code', 'ninja-forms-zoho-crm' ) => 'Contacts.Mailing Zip',
        __( 'Contact Mailing Country', 'ninja-forms-zoho-crm' ) => 'Contacts.Mailing Country',
        __( 'Contact Other Street', 'ninja-forms-zoho-crm' ) => 'Contacts.Other Street',
        __( 'Contact Other City', 'ninja-forms-zoho-crm' ) => 'Contacts.Other City',
        __( 'Contact Other State', 'ninja-forms-zoho-crm' ) => 'Contacts.Other State',
        __( 'Contact Other Code', 'ninja-forms-zoho-crm' ) => 'Contacts.Other Zip',
        __( 'Contact Other Country', 'ninja-forms-zoho-crm' ) => 'Contacts.Other Country',
        __( 'Contact Custom ->', 'ninja-forms-zoho-crm' ) => 'Contacts.custom',
        __( '-- Potential Mapping --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Potential Name', 'ninja-forms-zoho-crm' ) => 'Potentials.Potential Name',
        __( 'Potential Type', 'ninja-forms-zoho-crm' ) => 'Potentials.Type',
        __( 'Potential Lead Source', 'ninja-forms-zoho-crm' ) => 'Potentials.Lead Source',
        __( 'Potential Campaign Source', 'ninja-forms-zoho-crm' ) => 'Potentials.Campaign Source',
        __( 'Potential Amount', 'ninja-forms-zoho-crm' ) => 'Potentials.Amount',
        __( 'Potential Closing Date', 'ninja-forms-zoho-crm' ) => 'Potentials.Closing Date',
        __( 'Potential Next Step', 'ninja-forms-zoho-crm' ) => 'Potentials.Next Step',
        __( 'Potential Stage', 'ninja-forms-zoho-crm' ) => 'Potentials.Stage',
        __( 'Potential Probability', 'ninja-forms-zoho-crm' ) => 'Potentials.Probability',
        __( 'Potential Expected Revenue', 'ninja-forms-zoho-crm' ) => 'Potentials.Expected Revenue',
        __( 'Potential Description', 'ninja-forms-zoho-crm' ) => 'Potentials.Description',
        __( '-- Task Mapping --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Task Subject', 'ninja-forms-zoho-crm' ) => 'Tasks.Subject',
        __( 'Task Due Date', 'ninja-forms-zoho-crm' ) => 'Tasks.Due Date',
        __( 'Task Status', 'ninja-forms-zoho-crm' ) => 'Tasks.Status',
        __( 'Task Priority', 'ninja-forms-zoho-crm' ) => 'Tasks.Priority',
        __( 'Task Description', 'ninja-forms-zoho-crm' ) => 'Tasks.Description',
        __( '-- Note Mapping --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Note Title', 'ninja-forms-zoho-crm' ) => 'Notes.Note Title',
        __( 'Note Content', 'ninja-forms-zoho-crm' ) => 'Notes.Note Content',
        __( '-- Other Parameters --', 'ninja-forms-zoho-crm' ) => 'divider',
        __( 'Trigger Workflows', 'ninja-forms-zoho-crm' ) => 'Parameters.wfTrigger',
        __( 'Requires Approval', 'ninja-forms-zoho-crm' ) => 'Parameters.isApproval',
    );

    switch ($map) { //create custom selection lists for specific field types
        case 'textarea':
            $field_map_options = array(
                __( '- None', 'ninja-forms-zoho-crm' ) => 'none',
                __( 'Lead Description', 'ninja-forms-zoho-crm' ) => 'Description',
                __( 'Account Description', 'ninja-forms-zoho-crm' ) => 'Accounts.Description',
                __( 'Contact Description', 'ninja-forms-zoho-crm' ) => 'Contacts.Description',
                __( 'Potential Description', 'ninja-forms-zoho-crm' ) => 'Potentials.Description',
                __( 'Task Description', 'ninja-forms-zoho-crm' ) => 'Tasks.Description',
                __( 'Note Content', 'ninja-forms-zoho-crm' ) => 'Notes.Note Content',
                __( 'Lead Custom ->', 'ninja-forms-zoho-crm' ) => 'custom',
                __( 'Contact Custom ->', 'ninja-forms-zoho-crm' ) => 'Contacts.custom',
                __( 'Account Custom ->', 'ninja-forms-zoho-crm' ) => 'Accounts.custom',
            );

            break;

        case 'country':
            $field_map_options = array(
                __( '- None', 'ninja-forms-zoho-crm' ) => 'none',
                __( 'Lead Country', 'ninja-forms-zoho-crm' ) => 'Country',
                __( 'Account Billing Country', 'ninja-forms-zoho-crm' ) => 'Accounts.Billing Country',
                __( 'Account Shipping Country', 'ninja-forms-zoho-crm' ) => 'Accounts.Shipping Country',
                __( 'Contact Mailing Country', 'ninja-forms-zoho-crm' ) => 'Contacts.Mailing Country',
                __( 'Contact Other Country', 'ninja-forms-zoho-crm' ) => 'Contacts.Other Country',
                __( 'Custom ->', 'ninja-forms-zoho-crm' ) => 'custom'
            );

            break;

        default:
            $field_map_options = $complete_field_map_options; //set a default
    }

    return $field_map_options;
}

// Build an array of standard NF fields that can be mapped to Zoho CRM
function nfzohocrm_build_mappable_nf_fields_array() {

    $mappable_nf_fields_array = array(
        '_text' => '',
        '_hidden' => '',
        '_list' => '',
        '_checkbox' => '',
        '_number' => '',
        '_textarea' => 'textarea',
        '_country' => 'country',
    );

//    $mappable_nf_fields_array[ '_text' ] = '';
//    $mappable_nf_fields_array[ '_hidden' ] = '';
//    $mappable_nf_fields_array[ '_list' ] = '';
//    $mappable_nf_fields_array[ '_textarea' ] = 'textarea';
//    $mappable_nf_fields_array[ '_country' ] = 'country';

    return $mappable_nf_fields_array;
}

// Grab custom fields from other extensions
// Each new extension will require a function in here to build the array
function nfzohocrm_grab_custom_fields_from_extensions() {

    $extension_fields_array = array();

    // grab User Analytics fields
    global $NF_User_Analytics;

    // Make sure the $NF_User_Analytics variable is available
    if ( isset( $NF_User_Analytics ) ) {

        // access the get_ua_fields() method of the UA class
        $ua_fields = $NF_User_Analytics->get_ua_fields();

        // do whatever you need with the $ua_fields
        foreach ( $ua_fields as $key => $array ) {

            $extension_fields_array[ $key ] = '';
        }
    }

    return $extension_fields_array;
}
