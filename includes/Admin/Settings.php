<?php

if (!defined('ABSPATH'))
    exit;

/**
 * Class NF_ZohoCRM_Admin_Settings
 */
final class NF_ZohoCRM_Admin_Settings
{

    /**
     * Configured settings, optional fields removed, markup added
     *
     * Advanced commands can hide or display desired fields; HTML markup is
     * added to raw data where needed for user-friendly display
     * @var Array
     */
    protected $settings_array;

    /**
     * Readable comm data with HTML markup for display on the settings page
     * @var array
     */
    protected $marked_up_comm_data;

    /**
     * Support data with HTML markup for display on the settings page
     * @var array
     */
    protected $marked_up_support_data;

    /**
     * Account data with HTML markup for display on the settings page
     * @var array
     */
    protected $marked_up_account_data;

    public function __construct()
    {
        $comm_data_markup = NF_ZohoCRM()->factory->commDataMarkup();

        $this->marked_up_comm_data = $comm_data_markup->getMarkup();

        $support_markup = NF_ZohoCRM()->factory->supportMarkup();

        $this->marked_up_support_data = $support_markup->getMarkup();

        $account_markup = NF_ZohoCRM()->factory->accountDataMarkup();

        $this->marked_up_account_data = $account_markup->getMarkup();

        add_filter('ninja_forms_plugin_settings', array( $this, 'pluginSettings' ), 10, 1);

        add_filter('ninja_forms_plugin_settings_groups', array( $this, 'pluginSettingsGroups' ), 10, 1);
    }

    public function pluginSettings($settings)
    {
        $this->settings_array = NF_ZohoCRM()->config('PluginSettings');

        $this->settings_array[ 'comm_status' ][ 'html' ] = $this->marked_up_comm_data[ 'comm_status' ];

        $this->settings_array[ NF_ZohoCRM()->constants->refresh_token ][ 'html' ] = $this->marked_up_support_data[ NF_ZohoCRM()->constants->refresh_token ];

        $this->buildConnectionTestLink();

        $this->hideV1Credentials();

        $this->hideOAuthSetup();

        $this->addSupportSettings();

        $this->addDebugSettings();

        $this->addRetrievedFieldSettings();

        $this->addAccountSettings();

        $this->displayIfPresent();

        $settings[ NF_ZohoCRM()->constants->settings_group ] = $this->settings_array;

        return $settings;
    }

    /**
     * Hide v1 credentials unless specifically requested
     */
    protected function hideV1Credentials()
    {
        $command = NF_ZohoCRM()->stored_data->isAdvancedCommandSet('show_v1cred');

        if (FALSE === $command) {

            $support_mode_settings = array(
                NF_ZohoCRM()->constants->auth_token,
            );

            foreach ($support_mode_settings as $setting) {

                unset($this->settings_array[ $setting ]);
            }
        }
    }

    /**
     * On advanced command, hide the OAuth settings
     *
     * Used to de-clutter settings page
     */
    protected function hideOAuthSetup()
    {
        $command = NF_ZohoCRM()->stored_data->isAdvancedCommandSet('hide_setup');

        if (TRUE === $command) {

            $support_mode_settings = array(
                NF_ZohoCRM()->constants->client_id,
                NF_ZohoCRM()->constants->client_secret,
                NF_ZohoCRM()->constants->authorization_code,
                NF_ZohoCRM()->constants->refresh_token,
            );

            foreach ($support_mode_settings as $setting) {

                unset($this->settings_array[ $setting ]);
            }
        }
    }

    /**
     * Adds support settings HTML, removes key if advanced command not set
     *
     * Triggers on 'support'
     */
    protected function addSupportSettings()
    {
        $display_support = apply_filters('nfzohocrm_display_support', FALSE);

        if (FALSE == $display_support) {

            $support_mode_settings = array(
                NF_ZohoCRM()->constants->field_map_array,
                NF_ZohoCRM()->constants->structured_request_array,
                NF_ZohoCRM()->constants->structured_response,
            );

            foreach ($support_mode_settings as $setting) {

                unset($this->settings_array[ $setting ]);
            }
        } else {

            $this->settings_array[ NF_ZohoCRM()->constants->field_map_array ][ 'html' ] = $this->marked_up_comm_data[ NF_ZohoCRM()->constants->field_map_array ];

            $this->settings_array[ NF_ZohoCRM()->constants->structured_request_array ][ 'html' ] = $this->marked_up_comm_data[ NF_ZohoCRM()->constants->structured_request_array ];

            $this->settings_array[ NF_ZohoCRM()->constants->structured_response ][ 'html' ] = $this->marked_up_comm_data[ NF_ZohoCRM()->constants->structured_response ];
        }
    }

    /**
     * Adds debug settings, removes if debug advanced command is not set
     *
     * Triggers on 'debug'
     */
    protected function addDebugSettings()
    {
        $command = NF_ZohoCRM()->stored_data->isAdvancedCommandSet('debug');

        $support_mode_settings = array(
            NF_ZohoCRM()->constants->raw_response,
        );

        if (FALSE == $command) {

            foreach ($support_mode_settings as $setting) {

                unset($this->settings_array[ $setting ]);
            }
        } else {

            foreach ($support_mode_settings as $setting) {
                $this->settings_array[ $setting ][ 'html' ] = $this->marked_up_comm_data[ $setting ];
            }
        }
    }

    /**
     * Retrieves and extracts field list for each specified module
     *
     * Used primarily to get exact markup required for custom field maps, which
     * are not displayed in Zoho
     */
    protected function addRetrievedFieldSettings()
    {
        $module_array = NF_ZohoCRM()->advanced_commands->arrayAdvancedCommmand('display_fields_');

        if (FALSE == $module_array) {
            unset($this->settings_array[ NF_ZohoCRM()->constants->retrieved_field_names ]);
        } else {

            $markup = '<table>'; // initialize

            $markup .= '<tr><th>Module</th><th>Fields</th></tr>';

            $get_record_object = NF_ZohoCRM()->factory->getRecords();

            foreach ($module_array as $module) {

                $single_record = $get_record_object->getSingleRecord($module);

                if (!empty($module)) {
                    $markup .= '<tr><td>' . $module . '</td><td>' . implode(' , ', array_keys($single_record)) . '</td></tr>';
                }
            }


            $markup .= '</table>';

            $this->settings_array[ NF_ZohoCRM()->constants->retrieved_field_names ][ 'html' ] = $markup;
        }
    }

    /**
     * Adds account settings, removes if display_account advanced command is not set
     *
     * Triggers on 'display_account'
     */
    protected function addAccountSettings()
    {

        $command = NF_ZohoCRM()->stored_data->isAdvancedCommandSet('display_account');

        $support_mode_settings = array(
            NF_ZohoCRM()->constants->manual_field_map,
        );

        if (FALSE === $command) {

            foreach ($support_mode_settings as $setting) {

                unset($this->settings_array[ $setting ]);
            }
        } else {

            foreach ($support_mode_settings as $setting) {

                $this->settings_array[ $setting ][ 'html' ] = $this->marked_up_account_data[ $setting ];
            }
        }
    }

    /**
     * Remove a setting should it have nothing to display
     */
    public function displayIfPresent()
    {
        $support_mode_settings = array(
            NF_ZohoCRM()->constants->errors,
        );

        foreach ($support_mode_settings as $setting) {

            if (0 < strlen($this->marked_up_comm_data[ $setting ])) {

                $this->settings_array[ $setting ][ 'html' ] = $this->marked_up_comm_data[ $setting ];
            } else {

                unset($this->settings_array[ $setting ]);
            }
        }
    }

    /**
     * Add configured settings group to the NF groups
     * @param array $groups
     * @return array
     */
    public function pluginSettingsGroups($groups)
    {
        $groups[ NF_ZohoCRM()->constants->settings_group ] = NF_ZohoCRM()->config('PluginSettingsGroups');

        return $groups;
    }

    /**
     * Returns the listener link to test the API
     */
    protected function buildConnectionTestLink()
    {
        $url = home_url() . '/?nfzohocrm_instructions=test-connection';

        $this->settings_array[ 'nfzohocrm_authtoken_connection_test_link' ][ 'html' ] = '<a id="zoho-settings" href="' . $url . '">Click to test your Zoho connection</a>';
    }

}
