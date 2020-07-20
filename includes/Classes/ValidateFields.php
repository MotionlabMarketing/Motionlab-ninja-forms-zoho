<?php

/**
 * Class of static methods for validating form submission values
 *
 */
class ZohoCRM_ValidateFields
{

    public static function forceInteger($value_in)
    {
        $value_out = intval($value_in);

        return $value_out;
    }

    /**
     * Removes non-numeric values, keeps only decimal point
     *
     * @param mixed $value_in
     * @return
     */
    public static function removeNonNumeric($value_in)
    {
        $value_out = preg_replace('/[^0-9.]*/', "", $value_in);

        return $value_out;
    }

    public static function forceFloat($value_in)
    {
        $value_out = floatval($value_in);

        return $value_out;
    }

    public static function forceBoolean($value_in)
    {
        $temp_value = $value_in;

        $false_values = array(
            'false',
            'FALSE',
            'unchecked',
            'Unchecked',
        );

        if (in_array($temp_value, $false_values)) {
            $temp_value = FALSE;
        }

        $value_out = (bool) $temp_value;

        return $value_out;
    }

    public static function limit140Characters($value_in)
    {
        $value_out = substr($value_in, 0, 140);

        return $value_out;
    }

    public static function limit60Characters($value_in)
    {
        $value_out = substr($value_in, 0, 60);

        return $value_out;
    }

    public static function convertDateInterval($value_in)
    {
        $date_format = NF_ZohoCRM()->constants->date_format;

        $date = new DateTime;

        $date_2 = new DateTime;

        $date_interval = date_interval_create_from_date_string($value_in);

        date_add($date, $date_interval);

        if ($date == $date_2) {

            $value_out = $value_in;
        } else {

            $value_out = $date->format($date_format);
        }

        return $value_out;
    }

    public static function formatDate($value_in)
    {
        $value_out = date(NF_ZohoCRM()->constants->date_format, strtotime($value_in));

        return $value_out;
    }

    /**
     * Explodes a comma delimited string into an array
     * @param string $value_in
     * @return array
     */
    public static function commaExplode($value_in)
    {
        $value_out = explode(',', $value_in);

        return $value_out;
    }

    public static function addCurlyBraces($value_in)
    {

        if (is_array($value_in)) {
            $value_out = $value_in;

            foreach ($value_out as &$value) {
                $value = '{' . $value . '}';
            }
        } else {
            $value_out = '{' . $value_in . '}';
        }

        return $value_out;
    }

    /**
     * Replaces underscore '_' with space ' '
     * 
     * @param string $value_in
     * @return string
     */
    public static function replaceUnderscores($value_in)
    {
        $value_out = str_replace('_', ' ', $value_in);

        return $value_out;
    }

}
