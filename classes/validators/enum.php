<?php
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