<?php
function validate_minlen($field, $value, $validation_param)
{
    if (is_string($value) && strlen($value) < $validation_param) {
        return "The $field field must be at least $validation_param in length.";
    } else if (is_array($value) && count($value) < $validation_param) {
        return "The $field field must have at least $validation_param items.";
    }
}