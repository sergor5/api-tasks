<?php
/** 
 * Name: Max Length Validator
 * Description: This function is used to validate that a field is less than a specified length. 
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated
 * @param number $validation_param The maximum length of the field
 */
function validate_maxlen($field, $value, $validation_param)
{
    if (is_string($value) && strlen($value) > $validation_param) {
        return "The $field field must be less than $validation_param in length.";
    } else if (is_array($value) && count($value) > $validation_param) {
        return "The $field field must have less than $validation_param items.";
    }
}