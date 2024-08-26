<?php

namespace Nova\Route;

use InvalidArgumentException;
use Nova\HTTP\DynamicParameters;
use Nova\HTTP\IncomingRequestInterface;
use Nova\HTTP\Response;
use Nova\Middleware\Middleware;
use Nova\Middleware\MiddlewareHandler;
use Nova\View\View;

class RouteDispatcher
{
    /**
     * The list of registered routes.
     *
     * @var array
     */
    private static array $routes = [];

    /**
     * The list of route names.
     *
     * @var array
     */
    private static array $names = [];

    /**
     * The list of route middlewares.
     *
     * @var array
     */
    private static array $middlewares = [];

    /**
     * The list of route groups.
     *
     * @var array
     */
    private static array $groups = [];

    /**
     * @var RouteDispatcher|null
     */
    private static ?RouteDispatcher $instance;

    /**
     * @var DynamicParameters
     */
    private DynamicParameters $dynamicParameters;

    /**
     * @var Response|null
     */
    private ?Response $response;

    /**
     * @var View
     */
    private View $view;

    /**
     * The list of allowed HTTP methods.
     *
     * @var array
     */
    private array $allowedMethods = ['HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /**
     * An array of allowed route param type rules
     *
     * @var array
     */
    private static array $allowedParamTypeRules = [':num', ':text', ':any', ':regex'];

    /**
     * The mapping of parameters to their validation rules.
     *
     * @var array
     */
    private static array $ParamsAndRules = [];

    /**
     * The currently matched route.
     *
     * @var string
     */
    private string $currentRoute = '';

    public function __construct()
    {
        $this->dynamicParameters = new DynamicParameters();
        $this->view              = new View();
        $this->response          = new Response();
    }

    /**
     * Adds a route to the routing table.
     *
     * @param string $from
     * @param string|array|callable $to
     * @param string|array $method
     * @param string|null $name
     * @param bool $isGroup
     * @param string $prefix
     * @return void
     */
    public function add(string $from, string|array|callable $to, string|array $method, string $name = null, bool $isGroup = false, string $prefix = ''): void
    {
        $this->_add(
            $from,
            $to,
            $this->convert2uppercase($method),
            $name,
            $isGroup,
            $prefix
        );
    }

    /**
     * Add route middleware
     *
     * @param string|array $method
     * @param string|array|Middleware $middleware
     * @param string $from
     * @return void
     */
    public function addMiddleware(string|array $method, string|array|Middleware $middleware, string $from): void
    {
        $method = $this->convert2uppercase($method);

        $this->checkMethod($method);

        if (is_array($method)) {
            foreach ($method as $m) {
                self::$middlewares[$m][$from][] = $middleware;
            }
        } else {
            self::$middlewares[$method][$from][] = $middleware;
        }
    }

    /**
     * Add defined route to class
     *
     * @param string $from
     * @param string|array|callable $to
     * @param string|array $method
     * @param string|null $name
     * @param bool $isGroup
     * @param string $prefix
     * @return void
     */
    private function _add(string $from, string|array|callable $to, string|array $method, string $name = null, bool $isGroup = false, string $prefix = ''): void
    {
        if (empty($from) || !str_starts_with($from, '/')) {
            $from = '/' . $from;
        }

        if (strlen($from) !== 1 && str_ends_with($from, DIRECTORY_SEPARATOR)) {
            $from = substr($from, 0, -1);
        }

        $from = trim($from);

        if (is_string($to)) {
            $to = trim($to);
        }

        if ($isGroup) {
            $this->checkMethod($method);

            if (is_array($method)) {
                foreach ($method as $m) {
                    self::$groups[$prefix][$m][] = $from;
                }
            } else {
                self::$groups[$prefix][$method][] = $from;
            }
        }

        if ($name !== null) {
            $this->setRouteName($from, $name);
        }

        $this->setRoute($from, $to, $method);
    }

    /**
     * Get the routes group
     *
     * @return array
     */
    public function getRouteGroups(): array
    {
        return self::$groups;
    }

    /**
     * Dispatches the current request to the appropriate callback/controller.
     *
     * @return mixed The result of the callback/controller execution.
     */
    public function dispatch(IncomingRequestInterface $request): mixed
    {
        $middleware = new MiddlewareHandler();

        $request = $middleware->handleDefault($request);

        $userRequest = $request->getRequestUri();

        if (str_ends_with($userRequest, '/')) {
            // remove '/' if user request is end with '/'
            $userRequest = substr($userRequest, 0, -1);
        }

        if (strlen($userRequest) !== 1 && str_ends_with($userRequest, '/')) {
            $userRequest = substr($userRequest, 0, -1);
        }

        if ($userRequest === '') {
            $userRequest = '/';
        }

        $method = $request->getMethod();
        $result = $this->findMatchResult($method, $userRequest);

        $passRouteMiddleware = self::$middlewares[$method][$this->currentRoute] ?? [];

        if (!empty($passRouteMiddleware)) {
            $request = $middleware->handle($request, $passRouteMiddleware);
        }

        return $this->render($result, $request);
    }

    /**
     * Set route name
     *
     * @param string $route
     * @param string $name
     * @return void
     */
    private function setRouteName(string $route, string $name): void
    {
        $name = trim($name);

        self::$names[$route] = $name;
    }

    /**
     * Set route into class property
     *
     * @param string $route
     * @param string|array|callable $action
     * @param string|array $method
     *
     * @return void
     */
    private function setRoute(string $route, string|array|callable $action, string|array $method): void
    {
        $this->checkMethod($method);

        if (is_array($method)) {
            foreach ($method as $m) {
                self::$routes[$m][$route] = $action;
            }
        } else {
            self::$routes[$method][$route] = $action;
        }
    }

    /**
     * Renders the output based on the matched callback/controller.
     *
     * @param mixed $response The matched callback/controller.
     * @param IncomingRequestInterface $request
     * @return mixed The rendered output.
     */
    private function render(mixed $response, IncomingRequestInterface $request): mixed
    {
        if (!$response) {
            return $this->_render('notFound', '', 'url', $request->getFullUrl());
        }

        $type = function () use ($response) {
            return match (gettype($response)) {
                'string' => 'view',
                'array'  => 'controller',
                default  => 'callback'
            };
        };

        return $this->_render($type(), $response);
    }

    /**
     * Actually render users request output base on type
     *
     * @param string $type
     * @param mixed $resource
     * @param ...$data
     * @return mixed|void|null
     */
    private function _render(string $type, mixed $resource = '', ...$data)
    {
        switch ($type) {
            case 'view':

                $path = config('view.paths.view');

                $resolver = new ViewResolver($path, $resource);

                $resource = $resolver->resolve(fn ($file) => $this->render404('view', $file));

                return $this->response->setBody(
                    $this->view->render($resource, [], true)
                )->setStatus(200)
                ->send();

            case 'controller':

                $resolver = new ControllerResolver();

                $resolver->resolve(
                    $resource,
                    fn ($controller) => $this->_render('notFound', '', 'controller', $controller . '( )'),
                    fn ($controller, $method) => $this->_render('notFound', '', 'method', sprintf('%s::%s( )', $controller::class, $method))
                );

                return $resolver->action();

            case 'notFound':

                return $this->render404(...$data);

            default:

                return di()->callback($resource);
        }
    }

    /**
     * Render 404 exception view
     *
     * @param $type
     * @param $resource
     * @return Response
     */
    private function render404($type, $resource): Response
    {
        $environment = config('app.environment');

        $output = $this->view->render("nova_exception*{$environment}*404", [
            'type'     => $type,
            'resource' => $resource,
        ], true);

        $response = new Response($output, 404);

        return $response->send();
    }

    /**
     * Finds a matching callback/controller for the given request method and URL.
     *
     * @param string $method The HTTP request method.
     * @param string $request The requested URL.
     * @return mixed The matching callback/controller if found, otherwise false.
     */
    private function findMatchResult(string $method, string $request): mixed
    {
        if (!empty(self::$routes)) {
            foreach (self::$routes[$method] as $url => $action) {
                $matches = $this->matchRoute($url, $request);

                if ($matches !== false && $this->validateDynamicValues($matches)) {
                    // store current route to retrieve route middlewares
                    $this->currentRoute = $url;

                    // Set the dynamic param values to class
                    $this->setDynamicParams($request, $matches);

                    return $action;
                }
            }
        }

        return false;
    }

    /**
     * Matches a route pattern against the requested URL.
     *
     * @param string $route The route pattern to match.
     * @param string $request The requested URL.
     * @return array|false An array of dynamic values if the route matches, otherwise false.
     */
    private function matchRoute(string $route, string $request): array|false
    {
        $pattern = $this->getRoutePattern($route);

        if (preg_match($pattern, $request, $matches)) {
            return $this->extractDynamicValues($matches);
        }

        return false;
    }

    /**
     * Extracts dynamic values from the matched route pattern.
     *
     * @param array $matches The matches array from preg_match.
     * @return array An array of dynamic values extracted from the route pattern.
     */
    private function extractDynamicValues(array $matches): array
    {
        $dynamicKeys = array_filter(array_keys($matches), 'is_string');

        return array_intersect_key($matches, array_flip($dynamicKeys));
    }

    /**
     * Validates dynamic values against their specified parameter types.
     *
     * @param array $matches An array of dynamic values to validate.
     * @return bool True if all dynamic values are valid, otherwise false.
     */
    private function validateDynamicValues(array $matches): bool
    {
        foreach ($matches as $key => $value) {
            $key = self::$ParamsAndRules[$key] ? $key . ', :' . self::$ParamsAndRules[$key] : $key;

            if (!$this->doesParamHasType($key)) {
                // set dynamic param type as `:any` if specified type is not found
                $key .= ', :any';
            }

            $type = $this->getTypeOfParam($key);

            if (!$this->doesValueMatchParamType($value, $type, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check route method include in framework's supported http method
     *
     * @param string|array $method
     * @return void
     */
    private function checkMethod(string|array $method): void
    {
        if (is_array($method)) {
            foreach ($method as $m) {
                if (!is_string($m)) {
                    throw new \InvalidArgumentException("Argument #3 must be index array, ['GET', 'POST']");
                }

                if (!in_array($m, $this->allowedMethods)) {
                    throw new \RuntimeException("Unsupported HTTP method '{$m}' is used");
                }
            }
        } else {
            if (!in_array($method, $this->allowedMethods)) {
                throw new \RuntimeException("Unsupported HTTP method '{$method}' is used");
            }
        }
    }

    /**
     * Checks if the parameter has a type specified.
     *
     * @param string $parameter The parameter string to check.
     * @return bool True if the parameter has a type specified, false otherwise.
     */
    private function doesParamHasType(string $parameter): bool
    {
        $parameter = explode(',', $parameter);

        return count($parameter) > 1;
    }

    /**
     * Gets the type of the parameter.
     *
     * @param string $parameter The parameter string.
     * @return string The type of the parameter.
     */
    private function getTypeOfParam(string $parameter): string
    {
        $exploded = explode(',', $parameter);

        if (is_string($exploded[1]) && str_contains($exploded[1], '}')) {
            $exploded = trim(substr($exploded[1], 0, strpos($exploded[1], '}')));
        }

        return $this->doesParamHasType($parameter)
            ? (str_contains($exploded[1], '(')
                ? substr($exploded[1], 0, strpos($exploded[1], '('))
                : $exploded[1]
            )
            : ":any";
    }

    /**
     * Checks if the value matches the specified parameter type.
     *
     * @param mixed $value The value to check.
     * @param string $type The parameter type.
     * @param string $key The key associated with the parameter (optional).
     * @return bool True if the value matches the parameter type, false otherwise.
     * @throws InvalidArgumentException If the specified parameter type is not supported.
     */
    private function doesValueMatchParamType(mixed $value, string $type, string $key = ''): bool
    {
        $type = trim($type);

        if (!in_array($type, self::$allowedParamTypeRules)) {
            throw new InvalidArgumentException('Unsupported Route Param Type : ('. $type . ')');
        }

        switch ($type) {
            case ':text':
                return preg_match('/[^0-9]/', $value);

            case ':num':
                return preg_match('/^\d+$/', $value);

            case ':regex':
                return @preg_match('/' . $this->getParamRuleValue($key) . '/', $value) != 0;

            case ':any':
                return true;

            default:
                throw new InvalidArgumentException("Unsupported Route Param Type : ($type)");
        }
    }

    /**
     * Gets the rule value of the parameter type.
     *
     * @param string $string The string containing the parameter type rule.
     * @return string The rule value of the parameter type.
     */
    private function getParamRuleValue(string $string): string
    {
        preg_match_all('/\(([^()]|(?R))*\)/', $string, $matches);

        $raw = $matches[0][0];

        $raw = substr($raw, 1);

        return substr($raw, 0, -1);
    }

    /**
     * Generates a regex pattern for a route URL pattern.
     *
     * @param  string $route The route URL pattern.
     * @return string The regex pattern for the route URL pattern.
     */
    private function getRoutePattern(string $route): string
    {
        $route = $this->extractRuleFromParam($route, true);

        $pattern = preg_quote($route, '/');

        return '/^' . preg_replace('/\\\{(\w+)\\\}/', '(?P<$1>[\w-]+)', $pattern) . '$/';
    }

    /**
     * Extracts the rule part from a parameterized route.
     *
     * This method removes the rule part (e.g., ":num", ":text", ":any", ":regex(pattern)") from a parameterized route
     * and returns the modified route. Optionally, it can store the original route along with the rule in a
     * static property for future reference.
     *
     * @param  string $route The parameterized route containing rules.
     * @param  bool   $store Whether to store the original route along with the rule in a static property. Default is false.
     * @return string       The modified route with rules removed.
     */
    private function extractRuleFromParam(string $route, bool $store = false): string
    {
        if (str_contains($route, '{')) {
            $old = $route;
            $route = preg_replace('/,\s*:\w+(?:\([^)]*\))?/', '', $route);
            $route = explode(' ', $route);
            $route = implode('', $route);

            if ($store) {
                preg_match_all('/{(\w+),\s*:(\w+)(?:\((.*?)\))?}/', $old, $matches);

                $result = [];

                foreach ($matches[1] as $index => $key) {
                    $value = $matches[2][$index];
                    if (!empty($matches[3][$index])) {
                        $value .= '(' . $matches[3][$index] . ')';
                    }
                    $result[$key] = $value;
                }

                self::$ParamsAndRules = $result;
            }
        }

        return $route;
    }

    /**
     * Sets dynamic values in the controller for further processing.
     *
     * @param string $request The requested URL.
     * @param array $parameters An array of dynamic key-value pairs.
     * @return void
     */
    private function setDynamicParams(string $request, array $parameters): void
    {
        $this->dynamicParameters->set([$request => $parameters]);
    }

    /**
     * Get a route name with route
     *
     * @param string $route
     * @return mixed
     */
    public function getRouteName(string $route): mixed
    {
        $names = array_flip(self::$names);

        return $names[$route] ?? null;
    }

    /**
     * Get a route URL pattern by its name.
     *
     * @param string $name The name of the route.
     * @return string|null The URL pattern of the route if found, otherwise null.
     */
    public function getRouteWithName(string $name): mixed
    {
        return self::$names[$name] ?? null;
    }

    /**
     * Get framework's supported http methods
     *
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }

    /**
     * Converts HTTP method(s) to uppercase.
     *
     * This method accepts a string or an array of strings representing HTTP methods
     * and converts them to uppercase. If an array is provided, each element is processed
     * recursively.
     *
     * @param string|array $method The HTTP method(s) to be converted to uppercase.
     *                             It can be a single string or an array of strings.
     * @return array|string        The converted HTTP method(s) in uppercase. Returns
     *                             an array if the input was an array, otherwise returns a string.
     */
    private function convert2uppercase(string|array $method): array|string
    {
        if (is_array($method)) {
            foreach ($method as $index => $m) {
                $method[$index] = $this->convert2uppercase($method);
            }

            return $method;
        }

        return strtoupper($method);
    }

    /**
     * Get defined routes list
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Get the singleton instance of the RouteDispatcher.
     *
     * @return RouteDispatcher The instance of RouteDispatcher.
     */
    public static function getInstance(): RouteDispatcher
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
