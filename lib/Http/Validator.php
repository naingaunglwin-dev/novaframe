<?php

namespace NovaFrame\Http;

use NovaFrame\Http\Exceptions\ValidationException;

trait Validator
{
    /**
     * Validates request input with given rules.
     *
     * @param array $rules
     * @param array $labels
     *
     * @return true
     *
     * @throws ValidationException throw ValidationException on fail
     */
    public function validate(array $rules, array $labels = []): true
    {
        $validator = $this->validator();

        if (!empty($labels)) {
            $validator->setLabels($labels);
        }

        if (!$validator->validate($rules)) {
            throw new ValidationException($validator);
        }

        return true;
    }
}
