<?php

if ( !defined( 'ABSPATH' ) )
    exit;
/**
 * A lookup array of values to be converted to Zoho's boolean format
 */
return apply_filters( 'nfzohocrm_modify_zoho_bool_lookup', array(
    'checked' => 'true',
    'unchecked' => 'false'
        )
);


