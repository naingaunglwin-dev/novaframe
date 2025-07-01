<?php

namespace NovaFrame\Http\Exceptions;

class HttpException extends \RuntimeException
{
    private const DEFAULT_MESSAGES = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];

    public function __construct(
        private int $statusCode,
        ?string $message = null,
        int $code = 0,
        ?\Throwable $previous = null
    )
    {
        $message ??= self::DEFAULT_MESSAGES[$this->statusCode] ?? 'HTTP Error';
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->statusCode,
            'message' => $this->message,
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
