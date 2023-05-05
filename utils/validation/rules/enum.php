<?php
/** 
 * Name: Enum Validator
 * Description: This function is used to validate that a field is one of a set of values.
 * @param string $field The name of the field being validated
 * @param string $value The value of the field being validated
 * @param string|array $validation_param The parameter for the validation. Ex: 'HOURS,DAYS,WEEKS' or array('HOURS','DAYS','WEEKS')
 */

function validate_enum($field, $value, $validation_param)
{

    if ($value == null)
        return true;
    if (!is_array($validation_param))
        $validation_param = explode(',', $validation_param);

    if (in_array($value, $validation_param)) {
        return true;
    } else {
        return "The $field field must be one of the following: " . implode(', ', $validation_param);
    }
}