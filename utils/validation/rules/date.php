<?php
/** 
 * Name: Date Validator
 * Description: This function is used to validate that a field is a date string in ISO 8601 format with a UTC time zone indicator (YYYY-MM-DDTHH:MM:SSZ).
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated
 */

function validate_date($field, $value)
{
    if ($value == null)
        $value = "";
    if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/', $value)) {
        return true;
    } else {
        return "The $field field must be a date string in ISO 8601 format with a UTC time zone indicator (YYYY-MM-DDTHH:MM:SSZ)";
    }
}