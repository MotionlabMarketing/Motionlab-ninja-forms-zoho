<?php

if ( !defined( 'ABSPATH' ) )
    exit;
/*
 * The Comm Data options are stored in the nfzohocrm_comm_data option setting
 * and global variable of the same name.
 *
 * These get displayed as HTML in PluginSettings but aren't updated so that
 * the data contained is not overwritten by the settings page.
 * 
 * Each array is indexed by order of request
 */
return array(
    'comm_status' => array(), 
    'errors' => array(),
    'field_map_array' => array(),
    'request_array'=>array(),
    'structured_response'=>array(),
    'raw_response'=>array(),
    'settings_version' => 'unknown',
);
