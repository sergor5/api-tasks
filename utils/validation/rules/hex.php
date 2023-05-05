<?php
/** 
 * Name: Hex Validator
 * Description: This function is used to validate that a field is a valid hexadecimal value.
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated 
 */
function validate_hex($field, $value)
{
    if ($value == null)
        return true;
    if (preg_match('/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $value)) {
        return true;
    } else {
        return "The $field field must be a valid hexadecimal value.";
    }
}