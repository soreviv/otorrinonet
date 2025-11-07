<?php

namespace App\Core;

/**
 * A simple data validator class.
 *
 * Provides a clean way to validate arrays of data against a set of rules,
 * returning an array of error messages for any failed validations.
 */
class Validator
{
    /**
     * The array of validation errors.
     * @var array
     */
    private $errors = [];

    /**
     * Validates an array of data against a set of rules.
     *
     * @param array $data The data to validate (e.g., $_POST).
     * @param array $rules The validation rules.
     * @return self
     */
    public function validate(array $data, array $rules): self
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return $this;
    }

    /**
     * Apply a single validation rule to a field.
     *
     * @param string $field The name of the field.
     * @param mixed $value The value of the field.
     * @param string $rule The rule to apply.
     * @return void
     */
    private function applyRule(string $field, $value, string $rule): void
    {
        switch ($rule) {
            case 'required':
                if (empty(trim((string)$value))) {
                    $this->addError($field, "El campo {$field} es obligatorio.");
                }
                break;
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "El campo {$field} debe ser un correo electrónico válido.");
                }
                break;
        }
    }

    /**
     * Add an error message for a specific field.
     *
     * @param string $field The field that failed validation.
     * @param string $message The error message.
     * @return void
     */
    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    /**
     * Check if validation has failed.
     *
     * @return bool True if there are errors, false otherwise.
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
