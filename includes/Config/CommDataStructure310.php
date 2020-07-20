<?php

if ( !defined( 'ABSPATH' ) )
    exit;
/*
 * The Comm Data options are stored in the nfzohocrm_comm_data option setting
 * and global variable of the same name.
 *
 * These get displayed as HTML in PluginSettings but aren't updated so that
 * the data contained is not overwritten by the settings page.
 */
return array(
    'comm_status' => array(), 
    'field_map_array' => array(),
    
    'most_recent_raw_request' => '', //nfzohocrm_most_recent_raw_request
    'most_recent_raw_response' => '', //nfzohocrm_most_recent_raw_response
    'advanced_codes' => '', // optional commands
    'errors' => array(),
    'support_messages' => array(),
    
    'data_dump' => '', // dumps any desired data for quicker debug
);
