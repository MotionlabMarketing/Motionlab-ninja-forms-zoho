<?php

if ( !defined( 'ABSPATH' ) )
    exit;

return array(
    /*
      |-----------------
      | Zoho Field Map
      |-----------------
     */
    NF_ZohoCRM()->constants->field_map_action_key => array(
        'name' => NF_ZohoCRM()->constants->field_map_action_key,
        'type' => 'option-repeater',
        'label' => __( 'Zoho Field Map', 'ninja-forms-zoho-crm' ) . ' <a href="#" class="nf-add-new">' . __( 'Add New' ) . '</a>',
        'width' => 'full',
        'group' => 'primary',
        'tmpl_row' => 'nf-tmpl-zoho-custom-field-map-row',
        'value' => array(),
        'columns' => array(
            'form_field' => array(
                'header' => __( 'Form Field', 'ninja-forms-zoho-crm' ),
                'default' => '',
                'options' => array(),
            ),
            'field_map' => array(
                'header' => __( 'Zoho Field', 'ninja-forms-zoho-crm' ),
                'default' => '',
                'options' => array(), // created on constuction
            ),
            'custom_field' => array(
                'header' => __( 'Custom Field', 'ninja-forms-zoho-crm' ),
                'default' => '',
            ),
        ),
    ),
);


