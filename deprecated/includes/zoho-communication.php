<?php

/*--- NINJA FORMS ZOHO CRM INTEGRATION ---*/


/*----------
EXCHANGE DATA WITH ZOHO CRM THROUGH THE API
------------------------------------------------------------------------------------------------------------*/

/*----------
Insert Record
	- given the desired module and xml data, passes data to zoho and returns success or error result
-----*/

function lb3nf_zohocrm_insert_record( $xml_data , $module = 'lead' ){//insert record via xml into requested module

	global $nfzohocrm_site_options;

	$authtoken = $nfzohocrm_site_options[ 'nfzohocrm_authtoken' ];
	
	
	/*--- build command url ---*/
	
	$command_url = NFZOHOCRM_URI;

	switch( $module ){
	
		case 'lead':
			$command_url .= '/Leads/insertRecords?';
			break;
	}//end switch

	
	/*--- build body array ---*/
	
	$body_array = array();  //initialize body array
	$body_array[ 'authtoken' ] = $authtoken; 
	$body_array[ 'scope' ] =  NFZOHOCRM_SCOPE ;
	$body_array[ 'xmlData' ] = $xml_data;

	
	/*--- communicate and return response ---*/
	
	$response = wp_remote_post( 
		$command_url, 
		array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'sslverify' => FALSE,
			'body' => $body_array,
		)
	);
	
	if ( is_wp_error( $response ) ) {
	
		$error_message = $response->get_error_message();
		return $error_message;
	   
	} else {
	  
		return $response['body'];
	  
	}//end else
		
}//end lb3nf_zohocrm_insert_record

