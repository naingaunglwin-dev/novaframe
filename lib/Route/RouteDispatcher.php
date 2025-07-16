<?php

namespace NovaFrame\Route;

use NovaFrame\Encryption\Encryption;
use NovaFrame\Facade\Event;
use NovaFrame\Http\RedirectResponse;
use NovaFrame\Http\Request;
use NovaFrame\Http\Response;
use NovaFrame\Http\RouteParameter;
use NovaFrame\Kernel;
use NovaFrame\Middleware\Handler;
use NovaFrame\View\Renderer;
use NovaFrame\View\Template;

class RouteDispatcher
{
    /** 
     * The current matched route path
     * @var string
     */
    private string $matchedRoute = '';

    /**
     * @var RouteMiddleware|null
     */
    private ?RouteMiddleware $middleware;

    /**
     * RouteDispatcher constructor.
     *
     * @param Kernel $kernel Kernel instance to resolve controllers and methods
     * @param RouteCollection $collection Collection of registered routes
     */
    public function __construct(
        private readonly Kernel          $kernel,
        private readonly RouteCollection $collection,
    )
    {
    }

    /**
     * Dispatch the incoming HTTP request through middleware and route matching.
     *
     * @param Request  $request  The HTTP request object
     * @param Response $response The HTTP response object
     *
     * @return Response|RedirectResponse|mixed The HTTP response or redirect after processing
     */
    public function dispatch(Request $request, Response $response): mixed
    {
        $response = $this->getMiddleware()->handle($request, $response, global: true);

        if (($result = $this->checkCsrfValidation($response)) instanceof Response) {
            return $result;
        }

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        $url = parse_url($this->resolveRequestUrl($request->path()), PHP_URL_PATH);

        $matched = $this->match($request->method(), $url);

        if (!$matched) {
            return $this->render404('url', $request->fullUrl(), $response);
        }

        $response = $this->getMiddleware()->handle(
            $request,
            $response,
            $this->collection->getRouteList()[$request->method()][$this->matchedRoute]['middleware']
        );

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->handle($matched, $response);
    }

    /**
     * Checks CSRF validation by emitting the 'csrfValidation' event.
     *
     * If any listener returns a 403 Response, that response is returned immediately.
     *
     * @param Response $response The current HTTP response object
     *
     * @return bool|Response True if valid; otherwise a 403 Response
     */
    protected function checkCsrfValidation(Response $response): bool|Response
    {
        $results = Event::emitDeferred('csrfValidation');

        foreach ($results as $priorityGroup) {
            foreach ($priorityGroup as $callbacks) {
                foreach ($callbacks as $result) {
                    if ($result instanceof Response && $result->getStatusCode() === 403) {
                        $response->setStatusCode(403);
                        return $result;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Normalizes the request URL by ensuring it starts with a slash and removing trailing slashes.
     *
     * @param string $url Raw URL path
     *
     * @return string Normalized URL path
     */
    private function resolveRequestUrl($url): string
    {
        if (empty($url)) {
            $url = '/';
        }

        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return strlen($url) > 1 ? rtrim($url, '/') : $url;
    }

    /**
     * Render a 404 error page for missing routes, controllers, views, etc.
     *
     * @param string   $type     The type of missing resource ('url', 'view', 'controller', 'method')
     * @param string   $resource The resource identifier (URL, view name, controller class, etc)
     * @param Response $response The HTTP response object to set status and content
     *
     * @return Response The 404 Response with rendered error page
     */
    private function render404(string $type, string $resource, Response $response): Response
    {
        $renderer = new Renderer();

        $env = config('app.env');

        $output = $renderer->render("errors.{$env}.404", compact('type', 'resource'));

        return $response->setStatusCode(404)->setContent($output);
    }

    /**
     * Handle the matched route action, invoking controller, callback, or rendering view.
     *
     * @param array    $matched  The matched route information including 'action' and 'tokens'
     * @param Response $response The HTTP response object to modify and return
     *
     * @return Response The HTTP response after action handling
     */
    private function handle(array $matched, Response $response)
    {
        $action = $matched['action'];
        $detectedAction = $this->detectAction($action);

        switch ($detectedAction) {
            case 'view':
                if (!Template::resolve(DIR_APP . 'Views', $action)) {
                    return $this->render404('view', $action, $response);
                }

                $renderer = new Renderer();

                $output = $renderer->render($action);

                return $response->setContent($output)->setStatusCode(200);

            case 'controller':
                if (is_string($action)) {
                    if (!preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)@([a-zA-Z_][a-zA-Z0-9_]*)$/', $action, $tokens)) {
                        throw new \InvalidArgumentException("Invalid controller format; Correct Format 'Controller@method'");
                    };

                    $validate = $this->validateControllerAndMethod($tokens[1], $tokens[2], $response);

                    if ($validate instanceof Response) {
                        return $validate;
                    }

                    $controller = $tokens[1];
                    $method = $tokens[2];
                } else {
                    [$controller, $method] = $action;

                    $validate = $this->validateControllerAndMethod($controller, $method, $response);

                    if ($validate instanceof Response) {
                        return $validate;
                    }
                }

                $controller = $this->kernel->get($controller);

                if (method_exists($controller, 'init')) {
                    $this->kernel->get($controller, 'init');
                }

                return $this->kernel->get($controller, $method, $matched['tokens']);

            case 'callback':
                if (is_string($action) && str_contains($action, 'serialized:')) {
                    $decrypt = Encryption::decrypt(str_replace('serialized:', '', $action));
                    $action  = \Opis\Closure\unserialize($decrypt);
                }

                return $this->kernel->get($action, $matched['tokens']);
        }

        return $this->render404('', $action, $response);
    }

    /**
     * Validates that the given controller class and method exist.
     *
     * @param string   $controller Fully qualified controller class name
     * @param string   $method     Method name to call
     * @param Response $response   HTTP response to return on failure (404)
     *
     * @return bool|Response True if valid; otherwise 404 Response
     */
    private function validateControllerAndMethod($controller, $method, Response $response)
    {
        if (!class_exists($controller)) {
            return $this->render404('controller', $controller, $response);
        }

        if (!method_exists($controller, $method)) {
            if (is_object($controller)) {
                $controller = $controller::class;
            }
            return $this->render404('method',"$controller::$method", $response);
        }

        return true;
    }

    /**
     * Detects the type of action for a route based on the action variable.
     *
     * @param mixed $action The route action
     *
     * @return string One of 'view', 'controller', or 'callback'
     */
    private function detectAction(mixed $action): string
    {
        switch (gettype($action)) {
            case "string":
                if (str_contains($action, 'serialized:')) {
                    return 'callback';
                }

                if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)@([a-zA-Z_][a-zA-Z0-9_]*)$/', $action)) {
                    return 'controller';
                }

                return 'view';

            case 'array':
                return 'controller';

            default:
                return 'callback';
        }
    }

    /**
     * Attempts to match the request method and URL against registered routes.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $url    Request URL path
     *
     * @return array|false Matched route data including tokens or false if no match found
     */
    private function match($method, $url)
    {
        $routeList = $this->collection->getRouteList();

        if (empty($routeList) || empty($routeList[$method])) {
            return false;
        }

        foreach ($routeList[$method] as $route => $items) {
            $tokens = $this->tokenize($route, $url);

            if ($tokens && in_array($method, $items['method'])) {
                $items['tokens'] = $tokens;
                $this->matchedRoute = $route;

                return $items;
            }
        }

        return false;
    }

    /**
     * Extract named parameters from a route pattern given an actual URL.
     *
     * For example, route '/user/{id}' and url '/user/5' returns ['id' => '5'].
     *
     * @param string $route Route pattern with placeholders
     * @param string $url   Actual request URL
     *
     * @return array|false Array of matched tokens or false if no match
     */
    private function tokenize(string $route, string $url)
    {
        if ($url === '/') {
            return [$url];
        }

        $pattern = preg_quote($route, '/');

        $pattern = '/^' . preg_replace('/\\\{(\w+)\\\}/', '(?P<${1}>[\w-]+)', $pattern) . '$/';

        if (preg_match($pattern, $url, $tokens)) {
            $tokens = array_filter($tokens, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
            RouteParameter::set($tokens);

            return $tokens;
        }

        return false;
    }

    /**
     * Get or instantiate the middleware manager.
     *
     * @return RouteMiddleware The middleware handler instance
     */
    private function getMiddleware()
    {
        return $this->middleware ??= new RouteMiddleware(new Handler());
    }
}
