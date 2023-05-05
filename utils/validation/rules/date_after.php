<?php
/** 
 * Name: Date After Validator
 * Description: This function is used to validate that a field is a date string in ISO 8601 format with a UTC time zone indicator (YYYY-MM-DDTHH:MM:SSZ) and is after a specified date. 
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated
 * @param number $validation_param The date that the field must be after
 */
function validate_date_after($field, $value, $validation_param)
{
    if ($value !== '') {
        if ($value == null)
            return true;

        if (strtotime($value) > strtotime($validation_param)) {
            return true;
        } else {
            return "The $field field must be a date after $validation_param";
        }
    } else {
        return "The $field field must be a date string in ISO 8601 format with a UTC time zone indicator (YYYY-MM-DDTHH:MM:SSZ)";
    }

}