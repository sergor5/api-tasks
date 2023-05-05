<?php
class Validator
{
    private $data;
    private $rules;
    private $errors = [];

    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rules) {

            $value = $this->data[$field];
            foreach ($rules as $rule) {
                $valid = true;
                $params = [];
                $params["field"] = $field;
                $params["value"] = $value;

                $method = "validate_";
                if (strpos($rule, ':') !== false) {
                    list($rule_with_param, $validationParam) = explode(':', $rule, 2);
                    if (str_starts_with($validationParam, "$")) {
                        $validationParam = $this->data[substr($validationParam, 1)];
                    }
                    $params["validation_param"] = $validationParam;
                    $method .= $rule_with_param;
                } else {
                    $method .= $rule;
                }
                if (function_exists($method)) {
                    $valid = call_user_func_array($method, $params);
                } else {
                    throw new Exception("Validator method '$method' not found");
                }
                if (is_string($valid)) {
                    if (!isset($this->errors[$field]))
                        $this->errors[$field] = [];
                    array_push($this->errors[$field], $valid);
                }
            }
        }
        return empty($this->getErrors());
    }
}

require_once 'validators/required.php';
require_once 'validators/minlen.php';
require_once 'validators/maxlen.php';
require_once 'validators/date.php';
require_once 'validators/date_after.php';
require_once 'validators/hex.php';
require_once 'validators/enum.php';