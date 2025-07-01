<?php

namespace NovaFrame\Exception;

interface HandlerInterface
{
    public function handle(\Throwable $e);
}
