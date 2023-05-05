<?php
function validate_hex($field, $value)
{
    if ($value == null)
        $value = "";
    if (preg_match('/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $value)) {
        return true;
    } else {
        return "The $field field must be a valid hexadecimal value.";
    }
}