<?php

namespace NovaFrame;

use NovaFrame\Http\Response;

class Maintenance
{
    public function __construct(private string $file)
    {
    }

    public function run(Response $response)
    {
        ob_start();
        include $this->file;
        $content = ob_get_clean();

        return $response->setContent($content)
             ->setStatusCode(503);
    }
}
