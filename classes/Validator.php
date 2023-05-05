<?php
/** 
 * Name: Validator Class
 * Description: This class is used to validate data based on a set of rules.
 * @param array $data The data to be validated
 * @param array $rules The rules to be used for validation
 * 
 * @property array $data The data to be validated
 * @property array $rules The rules to be used for validation
 * @property array $errors The errors that occurred during validation
 * 
 * @method array getErrors() Returns the errors that occurred during validation
 * @method bool validate() Validates the data based on the rules
 * 
 */
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

//import all the rules
foreach (glob(__DIR__ . '/../utils/validation/rules/*.php') as $filename) {
    require_once $filename;
}