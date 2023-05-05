<?php
function validate_required($field, $value)
{
    if (empty($value)) {
        return "The $field field is required";
    }
}