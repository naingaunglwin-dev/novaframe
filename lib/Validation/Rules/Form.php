<?php

namespace NovaFrame\Validation\Rules;

use NovaFrame\Validation\Validation;

class Form extends Validation
{
    public function required(string $field, $value, array $params): bool
    {
        if (empty($value)) {
            $this->setError($params['label'], 'required', lang('validation.required', ['field' => $params['label']]));
            return false;
        }

        return true;
    }

    public function email(string $field, $value, array $params): bool
    {
        if (!empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        $this->setError($params['label'], 'is_mail', lang('validation.email', ['field' => $params['label']]));
        return false;
    }

    public function url(string $field, $value, array $params): bool
    {
        if (!empty($value) && filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        }

        $this->setError($params['label'], 'url', lang('validation.url', ['field' => $params['label']]));
        return false;
    }

    public function max(string $field, $value, array $params): bool
    {
        $length = is_string($value) ? strlen($value) : (int)$value;

        if (!empty($value) && $length <= (int)$params[0]) {
            return true;
        }

        $this->setError($params['label'], 'max', lang('validation.max', ['field' => $params['label'], 'max' => $value]));
        return false;
    }

    public function min(string $field, $value, array $params): bool
    {
        $length = is_string($value) ? strlen($value) : (int)$value;

        if (!empty($value) && $length >= (int)$params[0]) {
            return true;
        }

        $this->setError($params['label'], 'min', lang('validation.min', ['field' => $params['label'], 'min' => $params[0]]));
        return false;
    }

    public function equal(string $field, $value, array $params): bool
    {
        if (!empty($value) && $value === $params[0]) {
            return true;
        }

        $this->setError($params['label'], 'equals', lang('validation.equal', ['field' => $params['label'], 'equal' => $params[0]]));
        return false;
    }

    public function match(string $field, $value, array $params): bool
    {
        if (!empty($value) && isset($params['request'][$params[0]]) && $value == $params['request'][$params[0]]) {
            return true;
        }

        $this->setError($params['label'], 'match', lang('validation.match', ['field' => $params['label'], 'match' => $params[0]]));
        return false;
    }

    public function numeric(string $field, $value, array $params): bool
    {
        if (is_numeric($value)) {
            return true;
        }

        $this->setError($params['label'], 'numeric', lang('validation.numeric', ['field' => $params['label']]));
        return false;
    }

    public function string(string $field, $value, array $params): bool
    {
        if (is_string($value)) {
            return true;
        }

        $this->setError($params['label'], 'string', lang('validation.string', ['field' => $params['label']]));
        return false;
    }

    public function nullable(string $field, $value, array $params): bool
    {
        return true;
    }
}
