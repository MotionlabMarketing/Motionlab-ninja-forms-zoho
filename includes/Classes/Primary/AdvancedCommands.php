<?php

/**
 * Adds filters specified by an Advanced Command
 *
 * Enables dashboard setting to apply hard coded filters
 *
 * @author stuartlb3
 */
class ZohoCRM_AdvancedCommands
{

    /**
     * Advanced command array
     *
     * @var array
     */
    protected $advanced_command_array;

    public function __construct($advanced_command_array)
    {

        add_action('init', array( $this, 'applyAdvancedCommandFilters' ));

        $this->advanced_command_array = $advanced_command_array;
    }

    /**
     * Cycles through advanced commands and sets filter values
     */
    public function applyAdvancedCommandFilters()
    {

        if (defined('DISABLE_ZOHOCRM_ADVANCED_COMMANDS')) {

            return;
        }

        $advanced_codes_array = $this->advanced_command_array;

        $filters = NF_ZohoCRM::config('AdvancedCommandFilters');

        foreach ($advanced_codes_array as $code) {

            if (array_key_exists($code, $filters)) {

                $filter = $filters[ $code ][ 'filter' ];
                $callback = $filters[ $code ][ 'filter_callback' ];

                add_filter($filter, array( $this, $callback ));
            }
        }
    }

    /**
     * Returns TRUE boolean for use in filter callbacks
     *
     * @return TRUE
     */
    function returnTrue()
    {

        return TRUE;
    }

    /**
     * Returns crm.zoho.eu - the EU endpoint required for EU customers
     *
     * @return string EU endpoint
     */
    function returnEUEndpoint()
    {

        return 'crm.zoho.eu';
    }

    /**
     * Given an advanced command, evaluates if it is set or not
     * @param string $command
     * @return bool TRUE is command is set, FALSE if not set
     */
    public function isAdvancedCommandSet($command = '')
    {
        $advanced_codes_array = $this->advanced_command_array;

        if (in_array($command, $advanced_codes_array)) {

            $evaluation = TRUE;
        } else {

            $evaluation = FALSE;
        }

        return $evaluation;
    }

    /**
     * Searches advanced commands for variable values and returns variable
     *
     * Given a prefixed command, returns suffix, false on fail
     *
     * @param string $prefix
     * @return string|bool Description
     */
    public function variableAdvancedCommand($prefix = '')
    {
        $return = FALSE;

        $advanced_commands_array = $this->advanced_command_array;

        foreach ($advanced_commands_array as $advanced_command) {

            if (0 === strpos($advanced_command, $prefix)) {

                $return = str_replace($prefix, '', $advanced_command);

                break;
            }
        }

        return $return;
    }

    /**
     * Searches advanced commands for prefixed values and returns integer suffix
     *
     * Given a prefixed command, returns integer, false on fail
     *
     * @param string $prefix
     * @return integer|bool Description
     */
    public function intAdvancedCommmand($prefix = '')
    {
        $return = FALSE;

        $variable_advanced_command = $this->variableAdvancedCommand($prefix);

        if ($variable_advanced_command) {
            $return = intval($variable_advanced_command);
        }

        return $return;
    }

    /**
     * Searches advanced commands for prefixed values and returns array suffix
     *
     * Given a prefixed command, returns array, exploded on '-', false on fail
     *
     * @param string $prefix
     * @return array|bool Description
     */
    public function arrayAdvancedCommmand($prefix = '')
    {
        $return = FALSE;

        $variable_advanced_command = $this->variableAdvancedCommand($prefix);

        if ($variable_advanced_command) {

            $return = explode('-', $variable_advanced_command);
        }

        return $return;
    }

}
