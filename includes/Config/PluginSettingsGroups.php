<?php

if ( !defined( 'ABSPATH' ) )
    exit;

return apply_filters( 'nf_zohocrm_plugin_settings_groups', array(
    'id' => NF_ZohoCRM()->constants->settings_group,
    'label' => __( 'Zoho CRM Settings', 'ninja-forms-zoho-crm' ),
        ) );
