<?php

/**
 * Lookup array of custom validation functions
 * 
 * Array is keyed on a custom callback in curly braces; values are arrays
 * of ValidationClass validation methods
 *  {callback_key}=>array('validationMethod1','validationMethod2')
 * 
 * 
 * @var array
 */
return array(
    '{multipicklist}' => array( 'commaExplode' ),
    '{replace_underscores}' => array( 'replaceUnderscores' ),
    '{remove_nonnumeric}'=>array('removeNonNumeric'),
    '{force_boolean}'=>array('forceBoolean'),
    '{convert_date_interval}'=>array('convertDateInterval'),
    '{format_date}'=>array('formatDate'),
);
