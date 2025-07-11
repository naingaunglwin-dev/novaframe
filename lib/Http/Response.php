<?php

namespace NovaFrame\Http;

use NovaFrame\Facade\Session;
use NovaFrame\Http\Exceptions\UnsupportedHttpStatus;

class Response
{
    /**
     * An array of supported content types
     *
     * @var array|string[]
     */
    private array $contentTypes = [
        'application/java-archive',
        'application/EDI-X12',
        'application/EDIFACT',
        'application/javascript (obsolete)',
        'application/octet-stream',
        'application/ogg',
        'application/pdf',
        'application/xhtml+xml',
        'application/x-shockwave-flash',
        'application/json',
        'application/ld+json',
        'application/xml',
        'application/zip',
        'application/x-www-form-urlencoded',
        'audio/mpeg',
        'audio/x-ms-wma',
        'audio/vnd.rn-realaudio',
        'audio/x-wav',
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/tiff',
        'image/vnd.microsoft.icon',
        'image/x-icon',
        'image/vnd.djvu',
        'image/svg+xml',
        'multipart/mixed',
        'multipart/alternative',
        'multipart/related',
        'multipart/form-data',
        'text/css',
        'text/csv',
        'text/html',
        'text/javascript',
        'text/plain',
        'text/xml',
        'video/mpeg',
        'video/mp4',
        'video/quicktime',
        'video/x-ms-wmv',
        'video/x-msvideo',
        'video/x-flv',
        'video/webm',
        'application/vnd.android.package-archive',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.presentation',
        'application/vnd.oasis.opendocument.graphics',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.mozilla.xul+xml',
    ];

    /**
     *  An array of supported HTTP status codes.
     *
     * @var array
     */
    private array $statusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'See Other',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Content',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error'
    ];

    /**
     * Response Constructor
     *
     * @param string $content The response body content.
     * @param int $statusCode The HTTP status code.
     * @param array<string, string> $headers HTTP headers to include.
     */
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = [],
    )
    {
    }

    /**
     * Set the HTTP status code.
     *
     * @param int $statusCode
     * @return $this
     * @throws UnsupportedHttpStatus
     */
    public function setStatusCode(int $statusCode): Response
    {
        if (!isset($this->statusCodes[$statusCode])) {
            throw new UnsupportedHttpStatus($statusCode);
        }

        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the current HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Flush the output buffer.
     *
     * @return void
     */
    public function flush(): void
    {
        if (ob_get_level() > 1) {
            ob_flush();
        }

        flush();
    }

    /**
     * Set multiple headers.
     *
     * @param array<string, string> $headers
     * @param bool $overwrite
     * @return $this
     */
    public function setHeaders(array $headers, bool $overwrite = false): Response
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value, $overwrite);
        }

        return $this;
    }

    /**
     * Set a single header.
     *
     * @param string $key
     * @param string $value
     * @param bool $overwrite
     * @return $this
     */
    public function setHeader(string $key, string $value, bool $overwrite = false): Response
    {
        if (!$overwrite && isset($this->headers[$key])) {
            return $this;
        }

        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Get all response headers.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get a specific header by name.
     *
     * @param string $key
     * @param mixed|null $default
     * @return string|null
     */
    public function getHeader(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Set response content.
     *
     * @param string $content
     * @param bool $append
     * @return $this
     */
    public function setContent(string $content, bool $append = true): Response
    {
        if ($append) {
            $this->content .= $content;
        } else {
            $this->content = $content;
        }

        return $this;
    }

    /**
     * Get response content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Return a JSON response.
     *
     * @param array $data
     * @param int $status
     * @param array<string, string> $headers
     * @return $this
     */
    public function json(array $data, int $status = 200, array $headers = []): Response
    {
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $this->statusCode = $status;
        $this->setHeaders($headers);
        $this->setHeader('Content-Type', 'application/json', true);

        return $this;
    }

    /**
     * Return an HTML response.
     *
     * @param string $content
     * @param int $status
     * @param array<string, string> $headers
     * @return $this
     */
    public function html(string $content, int $status = 200, array $headers = []): Response
    {
        $this->content = $content;
        $this->statusCode = $status;
        $this->setHeaders($headers);
        $this->setHeader('Content-Type', 'text/html', true);

        return $this;
    }

    /**
     * Set caching headers.
     *
     * @param int $second
     * @return $this
     */
    public function cacheHeader(int $second): Response
    {
        return $this->setHeaders([
            'Cache-Control' => 'public, max-age=' . $second,
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + $second),
        ]);
    }

    /**
     * Send the response to the client.
     *
     * @return void
     */
    public function send()
    {
        http_response_code($this->statusCode);

        $this->sendHeaders();

        echo $this->content;
    }

    /**
     * Clean the current response.
     *
     * @return void
     */
    public function clean()
    {
        $this->content = '';
        $this->statusCode = 200;
        $this->headers = [];
    }

    /**
     * Create a redirect response.
     *
     * @param string $url
     * @return RedirectResponse
     */
    public function redirect(string $url = ''): RedirectResponse
    {
        return new RedirectResponse($url, $this->statusCode, $this->headers);
    }

    /**
     * Create a download response.
     *
     * @param string $filepath
     * @param string $filename
     * @param array<string, string> $headers
     * @return DownloadResponse
     */
    public function download(string $filepath, string $filename, array $headers = []): DownloadResponse
    {
        return new DownloadResponse($filepath, $filename, $this->statusCode, $headers);
    }

    /**
     * Redirect to previous URL if available.
     *
     * @return RedirectResponse
     */
    public function back(): RedirectResponse
    {
        return $this->redirect(Session::get('previous_url', Session::get('current_url')));
    }

    /**
     * Send all response headers to the client.
     *
     * @return void
     */
    protected function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        header(sprintf('HTTP/%s %d %s', Request::createFromGlobals()->protocol(), $this->statusCode, $this->statusCodes[$this->statusCode]));

        foreach ($this->headers as $key => $value) {
            if ($this->validateHeader($key, $value)) {
                header($key . ': ' . $value);
            }
        }
    }

    /**
     * Validate a header key and value.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    private function validateHeader(string $key, string $value)
    {
        $key   = trim($key);
        $value = trim($value);

        if (empty($key) || strlen($value) > 1024 || preg_match("/[\r\n]/", $value)) {
            return false;
        }

        foreach (['Referer', 'Origin'] as $header) {
            if ($key === $header && !filter_var($value, FILTER_VALIDATE_URL)) {
                return false;
            }
        }

        if (strtolower($key) === 'content-type') {
            foreach ($this->contentTypes as $type) {
                if (stripos($value, $type) === 0) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }
}
