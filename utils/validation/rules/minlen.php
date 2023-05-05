<?php
/** 
 * Name: Min Length Validator
 * Description: This function is used to validate that a field is greater than a specified length.
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated
 * @param number $validation_param The minimum length of the field
 */
function validate_minlen($field, $value, $validation_param)
{
    if (is_string($value) && strlen($value) < $validation_param) {
        return "The $field field must be at least $validation_param in length.";
    } else if (is_array($value) && count($value) < $validation_param) {
        return "The $field field must have at least $validation_param items.";
    }
}