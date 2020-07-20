<?php

if (!defined('ABSPATH'))
    exit;
/*
 * {advanced_command} => array(
 *      {filter_name} ,
 *      {function_that_returns_value_for_command}
 * )
 */
return array(
    'support' => array(
        'filter' => 'nfzohocrm_display_support',
        'filter_callback' => 'returnTrue',
    ),
    'keep_html_tags' => array(
        'filter' => 'nfzohocrm_keep_html_tags',
        'filter_callback' => 'returnTrue',
    ),
    'eu_endpoint' => array(
        'filter'=>'nfzohocrm_alt_endpoint',
        'filter_callback'=>'returnEUEndpoint',
    ),
);

