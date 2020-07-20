<?php

if (!defined('ABSPATH'))
    exit;

return array(
    'Leads' => array(
        'module_api_name' => 'Leads',
        'insert_endpoint' => 'Leads',
        'required_fields' => array(
            'Last_Name' => 'NFPlaceholder'
        ),
    ),
    'Accounts' => array(
        'module_api_name' => 'Accounts',
        'insert_endpoint' => 'Accounts',
    ),
    'Contacts' => array(
        'module_api_name' => 'Contacts',
        'insert_endpoint' => 'Contacts',
        'parents' => array(
            array(
                'parent_module' => 'Accounts',
                'parent_field' => 'Account_Name',
                'child_field' => 'Account_Name',
            ),
        ),
        'required_fields' => array(
            'Last_Name' => 'NFPlaceholder'
        ),
    ),
    'Potentials' => array(
        'module_api_name' => 'Potentials',
        'insert_endpoint' => 'Potentials',
    ),
    'Tasks' => array(
        'module_api_name' => 'Tasks',
        'insert_endpoint' => 'Tasks',
    ),
    'Notes' => array(
        'module_api_name' => 'Notes',
        'insert_endpoint' => 'Notes',
        'parents' => array(
            array(
                'parent_module' => 'Contacts',
                'parent_field' => 'new_id',
                'child_field' => 'Parent_Id',
            ),
            array(
                'parent_module' => 'Leads',
                'parent_field' => 'new_id',
                'child_field' => 'Parent_Id',
            ),
        ),
    ),
    'Quotes' => array(
        'module_api_name' => 'Quotes',
        'insert_endpoint' => 'Quotes',
        'parents' => array(
            array(
                'parent_module' => 'Potentials',
                'parent_field' => 'new_id',
                'child_field' => 'Deal_Name',
            ),
            array(
                'parent_module' => 'Contacts',
                'parent_field' => 'new_id',
                'child_field' => 'Contact_Name',
            ),
            array(
                'parent_module' => 'Accounts',
                'parent_field' => 'new_id',
                'child_field' => 'Account_Name',
            ),
        ),
        'children' => array(
            array(
                'child_module' => 'Products',
                'parent_field' => 'Product_Details'
            ),
            array(
                'child_module' => 'QuoteTags',
                'parent_field' => 'Tag'
            ),
        ),
    ),
    'Sales_Orders' => array(
        'module_api_name' => 'Sales_Orders',
        'insert_endpoint' => 'Sales_Orders',
        'parents' => array(
            array(
                'parent_module' => 'Potentials',
                'parent_field' => 'new_id',
                'child_field' => 'Deal_Name',
            ),
            array(
                'parent_module' => 'Contacts',
                'parent_field' => 'new_id',
                'child_field' => 'Contact_Name',
            ),
            array(
                'parent_module' => 'Accounts',
                'parent_field' => 'new_id',
                'child_field' => 'Account_Name',
            ),
        ),
        'children' => array(
            array(
                'child_module' => 'Products',
                'parent_field' => 'Product_Details'
            ),
            array(
                'child_module' => 'SalesOrderTags',
                'parent_field' => 'Tag'
            ),
        ),
    ),
    'Products' => array(
        'module_api_name' => 'Products',
        'insert_endpoint' => 'Products',
    ),
    'QuoteTags' => array(),
    'SalesOrderTags' => array(),
);

