<?php

/*----------
FORM-SPECIFIC OPTIONS
------------------------------------------------------------------------------------------------------------------------*/

/*----------
Create the form-specific options
-----*/

/*--- first, hook into the flow ---*/
add_action( 'admin_init' , 'nfzohocrm_admin_hook', 12 );

function nfzohocrm_admin_hook() {

	nfzohocrm_create_field_options();
	
}


/*----------
Create the form specific options
-----*/

function nfzohocrm_create_field_options(){

	global $nfzohocrm_site_options;	
	
	if( !isset( $nfzohocrm_site_options['nfzohocrm-add-to-leads'] ) ){ $nfzohocrm_site_options['nfzohocrm-add-to-leads'] = false;}
	//Add given form to leads?
	$args  = array(
		'page' => 'ninja-forms',
		'tab' => 'form_settings',
		'slug' => 'nfzohocrm_settings',
		'title' => __( 'Zoho CRM Settings' , 'ninja-forms-zoho-crm' ),
		'settings' => array(
			array(
				'name' => 'nfzohocrm-add-to-leads',
				'type' => 'checkbox',
				'label' => __('Add to Zoho' , 'ninja-forms-zoho-crm') ,
				'desc' => __('Do you want this form data added to your Zoho CRM?' , 'ninja-forms-zoho-crm' ),
				'default_value' => $nfzohocrm_site_options['nfzohocrm-add-to-leads'],
			),
		),
	); 
	 
	if( function_exists( 'ninja_forms_register_tab_metabox_options' ) ){
			
			ninja_forms_register_tab_metabox($args);

	}
	
} //end nfzohocrm_create_field_options

