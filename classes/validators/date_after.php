<?php
function validate_date_after($field, $value, $validation_param)
{
    echo "validate_date_after";
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