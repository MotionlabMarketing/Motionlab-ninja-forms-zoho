<?php

if ( !defined( 'ABSPATH' ) )
    exit;

/*
 * Plugin Name: Ninja Forms - Zoho CRM
 * Plugin URI: http://lb3computingsolutions.com
 * Description: Connect to your Zoho CRM account with the power and ease of Ninja Forms
 * Version: 3.4
 * Author: Stuart Sequeira
 * Author URI: http://lb3computingsolutions.com/about
 * Text Domain: ninja-forms-zoho-crm
 *
 * Copyright 2016 Stuart Sequeira.
 */

if ( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3.0.0', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

// plugin folder url
    if ( !defined( 'NF2ZOHOCRM_PLUGIN_URL' ) ) {
        define( 'NFZOHOCRM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }

// plugin folder path
    if ( !defined( 'NF2ZOHOCRM_PLUGIN_DIR' ) ) {
        define( 'NF2ZOHOCRM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    }

// plugin root file
    if ( !defined( 'NF2ZOHOCRM_PLUGIN_FILE' ) ) {
        define( 'NF2ZOHOCRM_PLUGIN_FILE', __FILE__ );
    }

    // deprecated version number - ensure constant calls out NF2
    if ( !defined( 'NF2ZOHOCRM_VERSION' ) ) {
        define( 'NF2ZOHOCRM_VERSION', '1.8.0' );
    }


    // define Zoho mode as 2.9x
    // Do NOT use NF2 because mode is shared by 2.9 and 3.0 versions
    if ( !defined( 'NFZOHOCRM_MODE' ) ) {
        define( 'NFZOHOCRM_MODE', '2.9x' );
    }
    include 'includes/Admin/Functions.php';
    include 'deprecated/ninja-forms-zoho-crm-deprecated.php';
} else {

    /*
     * Begin 3.0 version
     */

    // define Zoho mode as POST3
    if ( !defined( 'NFZOHOCRM_MODE' ) ) {
        define( 'NFZOHOCRM_MODE', 'POST3' );
    }

    //TODO: switch this to a class
    include 'includes/Admin/Functions.php';

    // TODO: switch this to a class
    include 'includes/Admin/Listener.php';

    /**
     * Class NF_ZohoCRM
     */
    final class NF_ZohoCRM {

        const VERSION = '3.4';
        const SLUG = 'zoho-crm';
        const NAME = 'Zoho CRM';
        const AUTHOR = 'Stuart Sequeira';
        const PREFIX = 'NF_ZohoCRM';

        /**
         * @var NF_ZohoCRM
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * @var ZohoCRM_Constants Provides constants for shared uses
         */
        public $constants;

        /**
         * @var ZohoCRM_StoredData Retrieves and stores data
         */
        public $stored_data;

        /**
         *
         * @var ZohoCRM_AdvancedCommands Applies advanced command filters
         */
        public $advanced_commands;

        /**
         *
         * @var ZohoCRM_FieldMap Provides field map data
         */
        public $field_map;

        /**
         * @var ZohoCRM_Factory Returns instantiated objects
         */
        public $factory;

        /**
         * Support data during communication process
         *
         * @var array
         */
        public $nfzohocrm_comm_data;

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_ZohoCRM Highlander Instance
         */
        public static function instance() {

            if ( !isset( self::$instance ) && !(self::$instance instanceof NF_ZohoCRM) ) {
                self::$instance = new NF_ZohoCRM();

                self::$dir = plugin_dir_path( __FILE__ );

                self::$url = plugin_dir_url( __FILE__ );

                /*
                 * Register our autoloader
                 */
                spl_autoload_register( array( self::$instance, 'autoloader' ) );
            }

            return self::$instance;
        }

        public function __construct() {

            // TODO: replace this
            nfzohocrm_load_globals();

            /*
             * Set up Licensing
             */
            add_action( 'admin_init', array( $this, 'setupLicense' ) );

            /*
             * Create Admin settings
             */
            add_action( 'ninja_forms_loaded', array( $this, 'setupAdmin' ), 15 );

            /*
             * Load primary classes
             */
            add_action( 'ninja_forms_loaded', array( $this, 'loadPrimaryClasses' ), 5 );

            /*
             * Register Actions
             */
            add_filter( 'ninja_forms_register_actions', array( $this, 'registerActions' ) );

            $this->nfzohocrm_comm_data = get_option( 'nfzohocrm_comm_data' );
        }

        public function registerActions( $actions ) {

            $actions[ 'addtozohocrm' ] = new NF_ZohoCRM_Actions_AddToZohoCRM();

            return $actions;
        }

        /**
         * Set up the licensing
         */
        public function setupLicense() {

            if ( !class_exists( 'NF_Extension_Updater' ) )
                return;

            new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }

        /**
         * Create the settings page
         */
        public function setupAdmin() {

            if ( !is_admin() )
                return;

            new NF_ZohoCRM_Admin_Settings();
        }

        /**
         * Loads primary classes available through NF_ZohoCRM()->
         *
         * Also load v1 Comm and Request classes
         */
        public function loadPrimaryClasses() {

            self::file_include( 'Classes/Primary', 'Constants' );
            $this->constants = new ZohoCRM_Constants();

            self::file_include( 'Classes/Primary', 'StoredData' );
            $this->stored_data = new ZohoCRM_StoredData();

            $advanced_commands = $this->stored_data->advancedCommands();
            self::file_include( 'Classes/Primary', 'AdvancedCommands' );
            $this->advanced_commands = new ZohoCRM_AdvancedCommands($advanced_commands);

            self::file_include( 'Classes/Primary', 'FieldMap' );
            $this->field_map = new ZohoCRM_FieldMap();

            self::file_include( 'Classes/Primary', 'Factory' );
            $this->factory = new ZohoCRM_Factory();


            self::file_include( 'Classes/Comm', 'ZohoRequestObject' );
            self::file_include( 'Classes/Comm', 'ZohoCommObject' );
        }

        public function get_nfzohocrm_comm_data() {

            $data = get_option('nfzohocrm_comm_data');

            return $data;
        }

        /**
         * Adds or updates a value in the zoho comm data array
         *
         * Requires an update to store the value in the database
         *
         * @param string $key
         * @param mixed $value
         * @param bool $append
         */
        public function modify_nfzohocrm_comm_data( $key = '', $value = '', $append = false ) {

            if ( 0 < strlen( $key ) || 0 < strlen( $value ) ) {
//                return;
            }

            if ( $append ) {
                $count = count( $this->nfzohocrm_comm_data[ $key ] );

                if ( 3 < $count ) {

                    array_shift( $this->nfzohocrm_comm_data[ $key ] );
                }

                $this->nfzohocrm_comm_data[ $key ][] = $value;
            } else {

                $this->nfzohocrm_comm_data[ $key ] = $value;
            }
        }

        /**
         * Updates the current value of the zoho comm data in the database
         *
         * Kept separate from the modify function to reduce db calls
         */
        public function update_nfzohocrm_comm_data(  ) {

            update_option( 'nfzohocrm_comm_data', $this->nfzohocrm_comm_data );
        }


        /**
         * Returns a configuration specified in a given Config file
         * @param string $file_name
         * @return mixed
         */
        public static function config( $file_name ) {

            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

        /**
         * Includes a specific file in an Includes directory
         *
         * @param string $sub_dir
         * @param string $file_name
         */
        public static function file_include( $sub_dir, $file_name ) {

            include_once self::$dir . 'includes/' . $sub_dir . '/' . $file_name . '.php';
        }

        /**
         * Creates a template for display
         *
         * @param string $file_name
         * @param array $data
         * @return mixed
         */
        public static function template( $file_name = '', array $data = array() ) {

            if ( !$file_name ) {
                return;
            }
            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }

        /*
         * Optional methods for convenience.
         */
        public function autoloader( $class_name ) {

            if ( class_exists( $class_name ) )
                return;

            if ( false === strpos( $class_name, self::PREFIX ) )
                return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';

            if ( file_exists( $classes_dir . $class_file ) ) {
                require_once $classes_dir . $class_file;
            }
        }

    }

    /**
     * The main function responsible for returning NF_ZohoCRM
     * Instance to functions everywhere.
     *
     * @since 3.0
     * @return NF_ZohoCRM Highlander Instance
     */
    function NF_ZohoCRM() {
        return NF_ZohoCRM::instance();
    }

    NF_ZohoCRM();
}

add_filter('ninja_forms_upgrade_settings', 'NF_ZohoCRM_Upgrade');

function NF_ZohoCRM_Upgrade($data) {

    /*
     * Sitewide settings
     * Found in deprecated settings
     */
    $plugin_settings = get_option('nfzohocrm_settings', array(
        'nfzohocrm_authtoken' => '',
            )
    );

    Ninja_Forms()->update_settings(array(
        'nfzohocrm_authtoken' => $plugin_settings['nfzohocrm_authtoken'],
            )
    );

    /*
     * Form settings
     * Found in deprecated form settings
     * Action settings found in AddtoZoho action
     */
    if (isset($data['settings']['nfzohocrm-add-to-leads']) && 1 == $data['settings']['nfzohocrm-add-to-leads']) {

        /*
         * Use addtoaction name
         */
        $new_action = array(
            'type' => 'addtozohocrm',
            'label' => __('Add to Zoho CRM', 'ninja-forms-zoho-crm'),
        );

        $fieldmap_lookup = nfzohocrm_upgrade_fieldmap_lookup();

        foreach ($data['fields'] as $key => $field) {

            /*
             * From deprecated field-registration
             */
            if(!isset($field['data']['nfzohocrm_field_map']) || 'none'==$field['data']['nfzohocrm_field_map']){
                // this field does not have a field map
                continue;
            }else{

                /*
                 * Check if value is in lookup array, if not, transfer existing value
                 * If not in lookup, it is a custom field
                 */
                if(isset($fieldmap_lookup[$field['data']['nfzohocrm_field_map']])){

                    $fieldmap_key = $fieldmap_lookup[$field['data']['nfzohocrm_field_map']];
                }else{

                    $fieldmap_key = $field['data']['nfzohocrm_field_map'];
                }


            }

            /*
             * Use ActionFieldMapSettings key
             */
            $new_action['zoho_field_map'][]=array(
                'form_field'=>'{field:' .$field['id'] . '}',
                'field_map'=>$fieldmap_key,
                'custom_field'=>$field['data']['nfzohocrm_custom_field_map'], // zoho does not have entry type

            );
        }

        $data[ 'actions' ][] = $new_action;
        update_option('new_action', $new_action);  // debug only
//        update_option('data_fields', $data['fields']);  // debug only
    }
// update_option('returned_data',$data);
    return $data;
}


/**
 * Converts the 2.9 map instructions into the 3.0 key
 * @return array Lookup array of map_instructions with $key
 */
function nfzohocrm_upgrade_fieldmap_lookup(){

     $standard_fields_lookup = include plugin_dir_path(__FILE__).'includes/Config/FieldMapLookup.php' ;

     $lookup_array = array(); // initialize

     foreach($standard_fields_lookup as $key=>$array){

         $lookup_array[$array['map_instructions']]=$key;

     }

     return $lookup_array;
}
