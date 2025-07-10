<?php

namespace NovaFrame\Validation;

use NovaFrame\Facade\Session;
use NovaFrame\Http\Request;
use NovaFrame\Validation\Exceptions\UnknownValidationRule;

class Validator
{
    /**
     * Validation rules per field
     *
     * @var array<string, string>
     */
    private array $rules = [];

    /**
     * Available validation rules mapping rule name to class
     *
     * @var array<string, class-string>
     */
    private array $availableRules;

    /**
     * The current HTTP request instance
     *
     * @var Request
     */
    private Request $request;

    /**
     * Optional human-friendly labels for fields
     *
     * @var array<string, string>
     */
    private array $labels = [];

    /**
     * Accumulated validation errors
     *
     * @var array
     */
    private array $error = [];

    /**
     * Parameters for rules that accept them
     *
     * @var array<string, array<string, array>>
     */
    private array $params = [];

    /**
     * Validator constructor.
     *
     * @param Rule $rule Rule handler instance, used to discover available validation rules.
     */
    public function __construct(private Rule $rule)
    {
        $this->availableRules = $this->rule->discover();
        $this->request = request();
    }

    /**
     * Set the validation rules.
     *
     * @param array<string, string> $rules Keyed by field name, with rule strings (pipe-separated).
     *
     * @return $this
     */
    public function rules(array $rules): Validator
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Set user-friendly labels for form fields.
     *
     * @param array<string, string> $labels
     *
     * @return $this
     */
    public function setLabels(array $labels): Validator
    {
        foreach ($labels as $field => $label) {
            $this->labels[$field] = $label;
        }

        return $this;
    }

    /**
     * Validate data against the defined rules.
     *
     * @param array<string, mixed> $data Data to validate.
     *
     * @return bool True if validation passes, false otherwise.
     *
     * @throws UnknownValidationRule When an undefined validation rule is used.
     */
    public function validate(array $data): bool
    {
        $resolved = $this->resolve($data);
        $result = [];

        foreach ($this->availableRules as $rule => $classname) {
            foreach ($resolved as $field => $value) {
                $input = $this->request->input($field);

                if (array_key_exists('nullable', $value) && empty($input)) {
                    continue;
                }

                foreach ($value as $k => $v) {
                    if ($k === $rule) {

                        $params = [];

                        if (isset($this->params[$field][$k])) {
                            foreach ($v as $param) {
                                $params[] = $param;
                            }
                        }

                        $params['label'] = $this->labels[$field] ?? $field;
                        $params['request'] = $this->request->all();

                        $class = app()->get($classname);
                        $result[$field][$k] = app()->get($class, $k, ['field' => $field, 'value' => $input, 'params' => $params]);

                        $this->setErrors($class->getErrors());
                    }
                }
            }
        }

        $fail = array_filter($result, fn($value) => in_array(false, $value, true));

        if (!empty($fail)) {
            return false;
        }

        return true;
    }

    /**
     * Merge errors from rule validation into internal error store.
     *
     * @param array<string, string> $errors
     * @return void
     */
    private function setErrors(array $errors): void
    {
        $this->error = array_merge($this->error, $errors);
    }

    /**
     * Get all validation errors.
     *
     * Example:
     *
     *```
     * [
     *     'label' => [
     *         'rule1' => 'message1',
     *         'rule2' => 'message2'
     *     ]
     * ]
     *```
     *
     * @return array<string, array<string, string>>  Key = label, Value = array of rule => message
     */
    public function getErrors(): array
    {
        return $this->error;
    }

    /**
     * Get all validation error messages as a flat list.
     *
     * Example:
     * ```
     * [
     *     'message1',
     *     'message2'
     * ]
     * ```
     *
     * @return string[] List of all error messages
     */
    public function getErrorMessages(): array
    {
        $messages = [];

        if (empty($this->error)) {
            return $messages;
        }

        foreach ($this->error as $label => $errors) {
            foreach ($errors as $message) {
                $messages[] = $message;
            }
        }

        return $messages;
    }

    /**
     * Shortcut to run validation using current rules.
     *
     * @return bool
     */
    public function run()
    {
        return $this->validate($this->rules);
    }

    /**
     * Resolve raw rule strings into structured array, extracting parameters.
     *
     * @param array<string, string> $rules
     * @return array<string, array<string, array<string>>>
     * @throws UnknownValidationRule
     */
    private function resolve(array $rules): array
    {
        $resolved = [];

        foreach ($rules as $field => $rule) {
            $rule = explode('|', $rule);

            foreach ($rule as $r) {
                if ($this->ruleHasParam($r)) {
                    $pattern = '/^(\w+)\[(.*?)\]$/';
                    if (preg_match($pattern, $r, $matches)) {
                        if (!in_array($matches[1], array_keys($this->availableRules))) {
                            throw new UnknownValidationRule($matches[1]);
                        }

                        $resolved[$field][$matches[1]] = explode(',', $matches[2]);
                        $this->params[$field][$matches[1]] = $resolved[$field][$matches[1]];
                    }
                } else {
                    $resolved[$field][$r] = [];
                }
            }
        }

        return $resolved;
    }

    /**
     * Check if a validation rule string contains parameters.
     *
     * @param string $rule
     * @return bool
     */
    private function ruleHasParam(string $rule): bool
    {
        return str_contains($rule, '[') && str_contains($rule, ']');
    }
}
