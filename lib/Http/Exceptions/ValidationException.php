<?php

namespace NovaFrame\Http\Exceptions;

use NovaFrame\Http\RedirectResponse;
use NovaFrame\Http\Response;
use NovaFrame\Validation\Validator;
use RuntimeException;

class ValidationException extends RuntimeException
{
    public function __construct(private Validator $validator)
    {
        parent::__construct('Validation Failed');
    }

    public function redirect(): RedirectResponse
    {
        return response()
            ->back()
            ->with('errors', $this->validator->getErrorMessages());
    }

    public function getErrorMessages(): array
    {
        return $this->validator->getErrorMessages();
    }

    public function getErrors(): array
    {
        return $this->validator->getErrors();
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

    public function toJsonResponse(): Response
    {
        return response()->json([
            'errors' => $this->getErrors(),
            'message' => $this->getMessage(),
        ], 422);
    }

    public function redirectTo(string $url): RedirectResponse
    {
        return redirect($url)
            ->with('errors', $this->validator->getErrorMessages());
    }
}
