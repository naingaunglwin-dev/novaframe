<?php

namespace NovaFrame\Validation;

class Validation
{
    /**
     * Stores error messages keyed by field name and optionally by rule.
     *
     * @var array<string, string|array<string, string>>
     */
    private array $messages = [];

    /**
     * Set a validation error message.
     *
     * If multiple rules per field are expected, this can be extended to store messages per rule.
     *
     * @param string $label   The field name or label.
     * @param string $rule    The rule name (unused in current storage format).
     * @param string $message The error message to associate with the field.
     *
     * @return $this
     */
    public function setError(string $label, string $rule, string $message)
    {
        $this->messages[$label][$rule] = $message;

        return $this;
    }

    /**
     * Get a specific error message by field name, and optionally by rule.
     *
     * @param string|null $label The field name.
     * @param string|null $rule  The rule name (only useful if messages are stored per rule).
     *
     * @return string|null The error message if found, or null.
     */
    public function getError(?string $label = null, ?string $rule = null)
    {
        if (!empty($label) || !empty($rule) && isset($this->messages[$label][$rule])) {
            return $this->messages[$label][$rule];
        }

        if (!empty($label) && isset($this->messages[$label])) {
            return $this->messages[$label];
        }

        return null;
    }

    /**
     * Get all validation error messages.
     *
     * @return array<string, string|array<string, string>>
     */
    public function getErrors()
    {
        return $this->messages;
    }
}
