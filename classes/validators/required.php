<?php
function validate_required($field, $value, $validation_param = true)
{
    if (empty($value)) {
        return "The $field field is required";
    }
}