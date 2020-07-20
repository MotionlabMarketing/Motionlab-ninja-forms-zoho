<?php

/*--- NINJA FORMS ZOHO CRM INTEGRATION ---*/


/*----------
CREATE OPTIONS PAGE AND GET SETTINGS FOR SITE-WIDE VALUES
------------------------------------------------------------------------------------------------------------*/

/*----------
Create options page location
-----*/

add_action('admin_menu', 'nfzohocrm_create_options_page_location', 100);

function nfzohocrm_create_options_page_location() {

	add_submenu_page(
		'ninja-forms', //parent slug
		__('Zoho CRM Settings', 'ninja-forms-zoho-crm'), //page title
		__('Zoho CRM Settings', 'ninja-forms-zoho-crm'), //menu title
		'manage_options', //capability
		'nfzohocrm-site-options', //menu-slug
		'nfzohocrm_site_options_display_page' //display function
	);
	
} // end nfzohocrm_create_options_page_location


/*----------
 Output the html for the options page 
-----*/ 

function nfzohocrm_site_options_display_page() {
 
	global $nfzohocrm_site_options;
 
	
	?>
	<div class="wrap">
		<?php screen_icon( 'options-general' );?>
		<h2><?php _e('Ninja Forms Zoho CRM Settings', 'ninja-forms-zoho-crm'); ?></h2>
 
		<form method="post" action="options.php" class="nfzohocrm_site_options_form">
		 
<?php
		settings_fields('nfzohocrm-site-options');
		do_settings_sections('nfzohocrm-site-options-section');

		echo nfzohocrm_output_comm_status();
		
?>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'ninja-forms-zoho-crm' ); ?>" />
			</p>			
		</form>
	</div><!--end .wrap-->
	<?php
	
} //end nfzohocrm_site_options_display_page


/*----------
CREATE OPTIONS FIELD AND SECTION
	Insert these options onto a menu page
	Currently, menu page is a custom settings page created under the Ninja Forms main menu page
-----*/

add_action( 'admin_init' , 'nfzohocrm_create_sitewide_settings');

function nfzohocrm_create_sitewide_settings(){

	/*--- register setting ---*/
	$settings_fields = 'nfzohocrm-site-options';
	$nfzohocrm_settings = 'nfzohocrm_settings';
		
	register_setting( $settings_fields, $nfzohocrm_settings, 'nfzohocrm_validate_settings' ); //whitelists our setting to allow it to appear in a given form	
	
	
	/*--- Add Settings Section ---*/
	
	$section_id = 'nfzohocrm_site_section'; //id for our section
	$section_title = __( 'Ninja Forms Zoho CRM Settings' , 'ninja-forms-zoho-crm' ); // about our section
	$section_output_function = 'nfzohocrm_section_output'; //
	
	$do_settings_section = 'nfzohocrm-site-options-section'; //on which page should our new section go
	
	add_settings_section(
		$section_id
		, $section_title
		, $section_output_function
		, $do_settings_section	
	);
	

	/*--- Create array to add each sitewide option ---*/
	
	$options_array = array();
	
	$options_array['authtoken' ] = array(
		'field_id' 		=> 'nfzohocrm_authtoken',
		'field_title' 	=> __( 'Zoho CRM Auth Token' , 'ninja-forms-zoho-crm' )
		,'field_output_function' 	=> 'nfzohocrm_authtoken_field_output'
	
	);

	$options_array[ 'display_raw_comm']  = array(
		'field_id' 		=> 'nfzohocrm_display_raw_comm',
		'field_title' 	=> __( 'Display the Raw Communication Data?' , 'ninja-forms-zoho-crm' )
		,'field_output_function' 	=> 'nfzohocrm_display_raw_comm_field_output'
	
	);
	
	
	/*--- Loop through each option array to add a field to a do_settings_section ---*/
	
	foreach( $options_array as $option){
	
		add_settings_field(
			$option[ 'field_id' ]//unique id for field
			, $option[ 'field_title' ]//field title
			, $option[ 'field_output_function' ]//function callback
			, $do_settings_section //on which page should our new field go
			, $section_id //in which settings section should our new field go
			//,$section='default'
			//,$args=array()
		);

	}
	
} // end nfzohocrm_create_sitewide_settings


/*----------
Output the settings section tagline 
-----*/

function nfzohocrm_section_output(){
	
	echo __("Please complete the necessary settings for your Zoho CRM account" , 'ninja-forms-zoho-crm' );
	
} // end nfzohocrm_section_output


/*----------
Output the authtoken form html
-----*/

function nfzohocrm_authtoken_field_output(){

	global $nfzohocrm_site_options;
	
ob_start(); ?>

	<input 
		id="nfzohocrm_authtoken"
		name="nfzohocrm_settings[nfzohocrm_authtoken]"
		size = "50"
		type="text" 
		value = "<?php if(isset( $nfzohocrm_site_options['nfzohocrm_authtoken'] )){  echo $nfzohocrm_site_options['nfzohocrm_authtoken'];}?>" 
	/>
	
<?php echo ob_get_clean();

} //end nfzohocrm_authtoken_field_output


function nfzohocrm_display_raw_comm_field_output(){

	global $nfzohocrm_site_options;
	
	
	if(isset( $nfzohocrm_site_options['nfzohocrm_display_raw_comm'] )){
	
		$checked = $nfzohocrm_site_options['nfzohocrm_display_raw_comm'];
	
	}else{ $checked = 'no'; }
	
	
ob_start(); ?>
	<label for="nfzohocrm_display_raw_comm-yes">
	<input 
		id="nfzohocrm_display_raw_comm-yes"
		name="nfzohocrm_settings[nfzohocrm_display_raw_comm]"
		type="radio" 
		value = "TRUE" 
		<?php checked( $checked , 'TRUE' , true  );?>
	/>
	Yes</label> <br />
	<label for="nfzohocrm_display_raw_comm-no">
	<input 
		id="nfzohocrm_display_raw_comm-no"
		name="nfzohocrm_settings[nfzohocrm_display_raw_comm]"
		type="radio" 
		value = "FALSE" 
		<?php checked( $checked , 'FALSE' , true  );?>
	/>
	No</label><br />
	
	
<?php echo ob_get_clean();

} //end nfzohocrm_display_raw_comm_field_output





/*----------
Validate the authtoken value before saving to database
-----*/

function nfzohocrm_validate_settings( $input ){

	$output = array();
	
	foreach( $input as $key => $value ){
	
		if( isset( $input[$key] ) ){
		
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		
		}// endif
	
	} //end foreach

	return apply_filters( 'nfzohocrm_validate_settings' , $output, $input );
	
} // end nfzohocrm_validate_settings


/*----------
	Create HTML for communication status
-----*/
function nfzohocrm_output_comm_status(){

	global $nfzohocrm_site_options;
	global $zoho_comm_status_default;
	global $nfzohocrm_comm_data;
	
	if( isset( $nfzohocrm_site_options['zoho_comm_status'] ) ){
	
		$zoho_comm_status = $nfzohocrm_site_options['zoho_comm_status'];
	
	}else{
	
		$zoho_comm_status = $zoho_comm_status_default;
	
	}
	
	if( isset( $nfzohocrm_site_options['nfzohocrm_display_raw_comm'] ) ){
	
		$display_raw_comm = $nfzohocrm_site_options['nfzohocrm_display_raw_comm'] == "TRUE";
	
	}else{ $display_raw_comm = FALSE; }
	
	
	if( isset( $nfzohocrm_comm_data['nfzohocrm_most_recent_raw_request'] ) ){
	
		$most_recent_raw_request =  $nfzohocrm_comm_data['nfzohocrm_most_recent_raw_request'];
	
	}else{ $most_recent_raw_request = __('None', 'ninja-forms-zoho-crm' );}
	
	if( isset( $nfzohocrm_comm_data['nfzohocrm_most_recent_raw_response'] ) ){
	
		$most_recent_raw_response =$nfzohocrm_comm_data['nfzohocrm_most_recent_raw_response'];
	
	} else{ $most_recent_raw_response = __('None', 'ninja-forms-zoho-crm' );}
		

ob_start(); ?>

	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Communication Status' , 'ninja-forms-zoho-crm' ); ?></th>
				<td>
				
<?php echo $zoho_comm_status; ?>

				</td>
			</tr>

<?php if( $display_raw_comm ) {?>		
			<tr valign="top">
				<th scope="row"><?php _e( 'Most recent raw request sent to Zoho' , 'ninja-forms-zoho-crm' ); ?></th>
				<td>
				
<?php echo $most_recent_raw_request; ?>

				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Most recent raw response from Zoho' , 'ninja-forms-zoho-crm' ); ?></th>
				<td>
				
<?php echo $most_recent_raw_response; ?>

				</td>
			</tr>			
<?php	} // end if $display_raw_com ?>			
			
			
			
			
		</tbody>
	</table>
	
<?php	



	$comm_status_string =ob_get_clean();

	return $comm_status_string;
	
} // end nfzohocrm_output_comm_status
