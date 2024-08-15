<?php

namespace Nova\Foundation;

use Nova\HTTP\IncomingRequest;
use Nova\HTTP\Response;

abstract class Controller
{
    /**
     * Initialize the controller.
     *
     * This method is executed before the actual request controller method is called.
     * It allows you to implement any logic that should run before handling the request,
     * such as filtering, authentication, or other setup tasks.
     *
     * @param IncomingRequest $request
     * @param Response $response
     */
    public function initialize(IncomingRequest $request, Response $response){}
}
