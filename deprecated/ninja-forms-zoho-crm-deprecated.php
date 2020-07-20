<?php

/*
 * Name: Ninja Forms - Zoho CRM 2.9
 * URI: http://lb3computingsolutions.com
 */



/* ----------
  ZOHO CONNECTIONS
  ----- */

if ( !defined( 'NFZOHOCRM_URI' ) ) {
    define( 'NFZOHOCRM_URI', 'https://crm.zoho.com/crm/private/xml' );
}

if ( !defined( 'NFZOHOCRM_SCOPE' ) ) {
    define( 'NFZOHOCRM_SCOPE', 'crmapi' );
}


/* ----------
  GLOBALS
  ----- */

$nfzohocrm_site_options = get_option( 'nfzohocrm_settings' );
$nfzohocrm_comm_data = get_option( 'nfzohocrm_comm_data' );


$end_of_log_entry = "\n\n --- END OF NINJA FORMS ZOHO CONNECTION LOG ENTRY --- \n\n";

$zoho_comm_status_default = apply_filters( 'nfzohocrm_modify_zoho_comm_status_default', "No communication has been detected.  Please test using your created form." );


/* ----------
  INCLUDES
  ---------------------------------------------------------------------------------------------------------- */

/* ----------
  Create options pages and settings required
  ------ */

include_once( NF2ZOHOCRM_PLUGIN_DIR . 'deprecated/includes/settings-deprecated.php');
include_once( NF2ZOHOCRM_PLUGIN_DIR . 'deprecated/includes/form-settings-deprecated.php');

/* ----------
  Create field options for mapping to Zoho
  ----- */

include_once( NF2ZOHOCRM_PLUGIN_DIR . 'deprecated/includes/field-registration.php');


/* ----------
  Process form data and build xml needed for communication with Zoho
  ----- */

include_once( NF2ZOHOCRM_PLUGIN_DIR . 'deprecated/includes/data-processing.php');

/* ----------
  Communication with Zoho via API
  ----- */

include_once( NF2ZOHOCRM_PLUGIN_DIR . 'deprecated/includes/ZohoCommObject.php' );

/*
 * Load Globals
 */
nfzohocrm_load_globals();
    
/* ----------
  LICENSING
  -------------------------------------------------------------------------------------------------------------- */

add_action( 'admin_init', 'nfzohocrm_extension_setup_license' );

function nfzohocrm_extension_setup_license() {
    if ( class_exists( 'NF_Extension_Updater' ) ) {
        $NF_Extension_Updater = new NF_Extension_Updater( 'Zoho CRM', NF2ZOHOCRM_VERSION, 'Stuart Sequeira', __FILE__ );
    }
}

/* ----------
  LANGUAGE
  -------------------------------------------------------------------------------------------------------------- */

add_action( 'plugins_loaded', 'nfzohocrm_extension_load_lang' );

function nfzohocrm_extension_load_lang() {

    /** Set our unique textdomain string */
    $textdomain = 'ninja-forms-zoho-crm';

    /** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
    $locale = apply_filters( 'plugin_locale', get_locale(), $textdomain );

    /** Set filter for WordPress languages directory */
    $wp_lang_dir = apply_filters(
            'ninja_forms_wp_lang_dir', WP_LANG_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' . $textdomain . '-' . $locale . '.mo'
    );

    /** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
    load_textdomain( $textdomain, $wp_lang_dir );

    /** Translations: Secondly, look in plugin's "lang" folder = default */
    $plugin_dir = basename( dirname( __FILE__ ) );
    $lang_dir = apply_filters( 'nfzohocrm_extension_lang_dir', $plugin_dir . '/lang/' );
    load_plugin_textdomain( $textdomain, FALSE, $lang_dir );
}

/* ----------
  HOOK INTO THE FLOW
  -------------------------------------------------------------------------------------------------- */

add_action( 'init', 'nfzohocrm_frontend_hook' );

function nfzohocrm_frontend_hook() {

    add_action( 'ninja_forms_post_process', 'nfzohocrm_process_form_to_insert_form_data' );
}
