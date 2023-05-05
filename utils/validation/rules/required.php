<?php
/** 
 * Name: Required Validator
 * Description: This function is used to validate that a field is not empty.
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated 
 */
function validate_required($field, $value)
{
    if (empty($value)) {
        return "The $field field is required";
    }
}