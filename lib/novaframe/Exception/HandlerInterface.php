<?php

namespace Nova\Exception;

interface HandlerInterface
{
    /**
     * Set the error, exception, and shutdown handlers.
     *
     * @return void
     */
    public function set(): void;

    /**
     * Handle PHP errors and exceptions.
     *
     * @param mixed $exception The exception object or error.
     * @return bool Whether the handling was successful.
     */
    public function handle(mixed $exception): bool;
}
