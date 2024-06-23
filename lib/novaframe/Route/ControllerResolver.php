<?php

namespace Nova\Route;

use Nova\Event\Event;
use Nova\HTTP\IncomingRequest;
use Nova\HTTP\Response;

class ControllerResolver
{
    /**
     * The instantiated controller object.
     *
     * @var object|null
     */
    private ?object $controller;

    /**
     * The method to invoke on the controller object.
     *
     * @var string|null
     */
    private ?string $method;

    public function __construct()
    {
        $this->controller = null;
        $this->method = null;
    }

    /**
     * Resolves the controller and method from the provided array.
     *
     * @param array $array An array containing the controller class name and method name.
     * @param callable $controllerException The callback function to handle controller not found exceptions.
     * @param callable $methodException The callback function to handle method not found exceptions.
     * @return ControllerResolver The resolved ControllerResolver instance.
     */
    public function resolve(array $array, callable $controllerException, callable $methodException): ControllerResolver
    {
        $controller = $array[0];
        $method = $array[1];

        if ($this->verifyController($controller)) {
            $this->controller = new $controller();
        } else {
            call_user_func($controllerException, $controller);
            exit();
        }

        if ($this->verifyMethod($controller, $method)) {
            $this->method = $method;
        } else {
            call_user_func($methodException, $controller, $method);
            exit();
        }

        return $this;
    }

    /**
     * Invokes the controller action.
     *
     * @return mixed The result of the controller action.
     */
    public function action(): mixed
    {
        if (method_exists($this->controller, 'initialize')) {
            Event::trigger(
                'controller_initialize',
                $this->controller, IncomingRequest::createFromGlobals(),
                new Response()
            );
        }

        return resolver($this->controller)->method($this->method);
    }

    /**
     * Verifies the existence of the controller class.
     *
     * @param string $controller The name of the controller class.
     * @return bool True if the controller class exists; otherwise, false.
     */
    private function verifyController(string $controller): bool
    {
        if (!class_exists($controller)) {
            return false;
        }

        return true;
    }

    /**
     * Verifies the existence of the controller method.
     *
     * @param string $controller The name of the controller class.
     * @param string $method The name of the controller method.
     * @return bool True if the controller method exists; otherwise, false.
     */
    private function verifyMethod(string $controller, string $method): bool
    {
        if (!method_exists($controller, $method)) {
            return false;
        }

        return true;
    }
}
