<?php
function validate_maxlen($field, $value, $validation_param)
{
    if (is_string($value) && strlen($value) > $validation_param) {
        return "The $field field must be less than $validation_param in length.";
    } else if (is_array($value) && count($value) > $validation_param) {
        return "The $field field must have less than $validation_param items.";
    }
}