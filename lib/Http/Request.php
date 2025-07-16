<?php

namespace NovaFrame\Http;

use NovaFrame\Facade\Env;
use NovaFrame\RuntimeEnv;
use NovaFrame\Storage\Storage;
use NovaFrame\Validation\Rule;
use NovaFrame\Validation\Validator;

class Request
{
    use \NovaFrame\Http\Validator;

    /**
     * Raw and sanitized request input arrays (get, post, cookie, files)
     *
     * @var array<string, array>
     */
    private array $request;

    /**
     * Request headers
     *
     * @var array<string, string>
     */
    private array $headers;

    /**
     * Raw parsed JSON body
     *
     * @var array|null
     */
    private ?array $rawBody = null;

    /**
     * Sanitized JSON body
     *
     * @var array|null
     */
    private ?array $body = null;

    /**
     * Server variables (e.g. $_SERVER)
     *
     * @var array<string, mixed>
     */
    private array $server;

    /**
     * Request host
     *
     * @var string|null
     */
    private ?string $host = null;

    /**
     * Request scheme (http/https)
     *
     * @var string|null
     */
    private ?string $scheme = null;

    /**
     * Validator instance
     *
     * @var Validator|null
     */
    private ?Validator $validator = null;

    /**
     * File upload storage handler
     *
     * @var Storage|null
     */
    private ?Storage $storage = null;

    /**
     * Constructor for Request
     *
     * @param array|null $get
     * @param array|null $post
     * @param array|null $cookie
     * @param array|null $files
     * @param array|null $server
     */
    public function __construct(
        ?array $get = null,
        ?array $post = null,
        ?array $cookie = null,
        ?array $files = null,
        ?array $server = null,
    )
    {
        $this->initialize($get, $post, $cookie, $files, $server);
    }

    /**
     * Create request from superglobals
     *
     * @return static
     */
    public static function createFromGlobals(): Request
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    private function initialize($get, $post, $cookie, $files, $server): void
    {
        $this->request['raw'] = [
            'get'    => $get ?? $_GET,
            'post'   => $post ?? $_POST,
            'cookie' => $cookie ?? $_COOKIE,
            'files'  => $files ?? $_FILES,
        ];

        $this->request['sanitized'] = [
            'get'    => RequestSanitizer::sanitize('get', $this->request['raw']['get']),
            'post'   => RequestSanitizer::sanitize('post', $this->request['raw']['post']),
            'cookie' => RequestSanitizer::sanitize('cookie', $this->request['raw']['cookie']),
            'files'  => RequestSanitizer::sanitize('files', $this->request['raw']['files']),
        ];

        $this->headers = RuntimeEnv::envIs('cli') ? [] : getallheaders();
        $this->rawBody    = json_decode(file_get_contents('php://input'), true) ?: [];
        $this->body       = !empty($this->rawBody) ? RequestSanitizer::sanitizeBody($this->rawBody) : [];
        $this->server  = $server ?? $_SERVER;
    }

    /**
     * Returns server data or a specific server key.
     */
    public function server(?string $key = null, $default = null)
    {
        if (empty($key)) {
            return $this->server;
        }

        return $this->server[$key] ?? $default;
    }

    /**
     * Gets server port.
     */
    public function port()
    {
        return $this->server['SERVER_PORT'];
    }

    /**
     * Gets the host name from headers or server.
     */
    public function host()
    {
        return $this->host ??= (
            $this->headers['HOST'] ?? $this->server['HTTP_HOST']
        );
    }

    /**
     * Returns a header by name.
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Returns all request headers.
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Returns parsed JSON body or a specific key (optionally raw).
     */
    public function body(?string $key = null, $default = null, bool $raw = false)
    {
        $body = $raw ? $this->rawBody : $this->body;

        if ($key === null) return $body;

        return $this->traverse($body ?? [], $key, $default);
    }

    /**
     * Returns raw JSON body data.
     */
    public function rawBody(?string $key = null, $default = null)
    {
        return $this->body($key, $default, true);
    }

    /**
     * Returns request protocol (e.g., HTTP/1.1).
     */
    public function protocol()
    {
        return $this->server['SERVER_PROTOCOL'];
    }

    /**
     * Gets the request scheme (http/https).
     */
    public function scheme()
    {
        return $this->scheme ??= $this->server['REQUEST_SCHEME'] ??= ((!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ? 'https' : 'http');
    }

    /**
     * Gets the request IP address.
     */
    public function ip()
    {
        return $this->server['REMOTE_ADDR'];
    }

    /**
     * Returns all request data (GET, POST, JSON, COOKIES, HEADERS).
     */
    public function all(bool $raw = false): array
    {
        return array_merge(
            $this->getFromRequestUponSecureStatus('get', $raw),
            $this->getFromRequestUponSecureStatus('post', $raw),
            $this->getFromRequestUponSecureStatus('cookie', $raw),
            $this->body ?? [],
            $this->headers
        );
    }

    /**
     * Generates base URL for the request.
     */
    public function baseurl(string $uri = '')
    {
        if (Env::has('APP_URL')) {
            $appUrl = Env::get('APP_URL');

            $appUrl = rtrim($appUrl, '/');
            $uri = ltrim($uri, '/');

            return $appUrl . '/' . $uri;
        }

        $scheme     = $this->scheme();
        $host       = $this->host();
        $scriptName = $this->server['SCRIPT_NAME'] ?? '';
        $basepath   = rtrim(str_replace(basename($scriptName), '', $scriptName), '/');

        return rtrim("$scheme://$host$basepath", '/') . '/' . $uri;
    }

    /**
     * Returns URI path (with or without query).
     */
    public function path(bool $withQuery = false): string
    {
        $basepath = parse_url($this->baseurl(), PHP_URL_PATH);

        if (Env::has('APP_URL')) {
            $env = parse_url(Env::get('APP_URL'), PHP_URL_PATH);

            $env ??= '';

            $env = rtrim($env, '/');

            if ($env === $this->server['REQUEST_URI']) {
                $this->server['REQUEST_URI'] .= '/';
            }
        }

        $request = $this->server['REQUEST_URI'];

        if ($basepath !== '/') {
            $request = str_replace($basepath, '', $this->server['REQUEST_URI']);
        }

        return $withQuery ? $request : $this->trimQueryString($request);
    }

    /**
     * Returns full request URL.
     */
    public function fullUrl(bool $withQuery = false): string
    {
        return rtrim($this->baseurl(), '/') . '/' . ltrim($this->path($withQuery), '/');
    }

    /**
     * Returns validator instance.
     */
    public function validator(): Validator
    {
        if (!isset($this->validator)) {
            $this->validator = new Validator(new Rule());
        }

        return $this->validator;
    }

    /**
     * Gets the request method (e.g. GET, POST).
     */
    public function method(bool $lowercase = false): string
    {
        $method = $this->server['REQUEST_METHOD'];

        return $lowercase ? strtolower($method) : strtoupper($method);
    }

    /**
     * Checks if the request is secure (HTTPS).
     */
    public function secure(): bool
    {
        return $this->scheme() === 'https';
    }

    /**
     * Determines if the request is via AJAX.
     */
    public function isAjax(): bool
    {
        return strtolower($this->header('X-Requested-With') ?? '') === 'xmlhttprequest';
    }

    /**
     * Determines if the request is sending JSON.
     */
    public function isJson(): bool
    {
        return str_contains($this->header('Content-Type') ?? '', 'application/json');
    }

    /**
     * Returns GET input or the entire GET array (optionally raw).
     */
    public function query(?string $key = null, $default = null, bool $raw = false)
    {
        if (empty($key)) {
            return $this->getFromRequestUponSecureStatus('get', $raw);
        }

        if ($this->checkRequestWithDotNotion($key, 'get', $raw)) {
            return $this->traverse($this->getFromRequestUponSecureStatus('get', $raw), $key, $default);
        }

        return $default;
    }

    /**
     * Returns raw GET input.
     */
    public function rawQuery(?string $key = null, $default = null)
    {
        return $this->query($key, $default, true);
    }

    /**
     * Returns POST input or the entire POST array (optionally raw).
     */
    public function post(?string $key = null, $default = null, bool $raw = false)
    {
        if (empty($key)) {
            return $this->getFromRequestUponSecureStatus('post', $raw);
        }

        if ($this->checkRequestWithDotNotion($key, 'post', $raw)) {
            return $this->traverse($this->getFromRequestUponSecureStatus('post', $raw), $key, $default);
        }

        return $default;
    }

    /**
     * Returns raw POST input.
     */
    public function rawPost(?string $key = null, $default = null)
    {
        return $this->post($key, $default, true);
    }

    /**
     * Returns cookie input or the full cookie array (optionally raw).
     */
    public function cookie(?string $key = null, $default = null, bool $raw = false)
    {
        if (empty($key)) {
            return $this->getFromRequestUponSecureStatus('cookie', $raw);
        }

        if ($this->checkRequestWithDotNotion($key, 'cookie', $raw)) {
            return $this->traverse($this->getFromRequestUponSecureStatus('cookie', $raw), $key, $default);
        }

        return $default;
    }

    /**
     * Returns raw cookie input.
     */
    public function rawCookie(?string $key = null, $default = null)
    {
        return $this->cookie($key, $default, true);
    }

    /**
     * Retrieves an input from post, get, or body.
     */
    public function input(string $key, $default = null, bool $raw = false)
    {
        $sources = [
            $this->getFromRequestUponSecureStatus('post', $raw),
            $this->getFromRequestUponSecureStatus('get', $raw),
            $raw ? $this->rawBody : $this->body
        ];

        foreach ($sources as $source) {
            $k = explode('.', $key)[0];

            if (isset($source[$k])) {
                return $this->traverse($source, $key, $default);
            }
        }

        return $default;
    }

    /**
     * Raw version of input().
     */
    public function rawInput(string $key, $default = null)
    {
        return $this->input($key, $default, true);
    }

    /**
     * Gets uploaded file info by input name.
     */
    public function file(string $key, $default = null)
    {
        return $this->request['sanitized']['files'][$key] ?? $default;
    }

    /**
     * Get a route parameter from the current request.
     *
     * @param string $name The parameter name to retrieve.
     * @param mixed|null $default The default value if parameter is not set.
     * @return mixed The route parameter value or default.
     */
    public function route(string $name, $default = null): mixed
    {
        return RouteParameter::get($name, $default);
    }

    private function traverse(array $source, string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);

        $arr = $source;

        foreach ($keys as $key) {
            if (!is_array($arr) || !array_key_exists($key, $arr)) {
                return $default;
            }
            $arr = $arr[$key];
        }

        return $arr;
    }

    private function trimQueryString(string $url): string
    {
        if ($pos = strpos($url, '?')) {
            return substr($url, 0, $pos);
        }

        return $url;
    }

    private function checkRequestWithDotNotion($key, $source, $raw): bool
    {
        return isset($this->getFromRequestUponSecureStatus($source, $raw)[explode('.', $key)[0]]);
    }

    private function getFromRequestUponSecureStatus($source, $raw)
    {
        return $this->request[$raw ? 'raw' : 'sanitized'][$source];
    }
}
