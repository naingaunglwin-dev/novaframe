<?php

/**
 * This file is part of NOVA FRAME framework
 *
 * @copyright (c) Naing Aung Lwin
 * @link https://github.com/naingaunglwin-dev/novaframe
 * @licence MIT
 */

namespace Nova\HTTP;

class Response implements ResponseInterface
{
    /**
     *  An array of supported content types
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
        ' image/gif',
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
     * @var string The content of the response.
     */
    private string $content = '';

    /**
     * @var int The HTTP status code of the response.
     */
    private int $statusCode = 200;

    /**
     * @var array The headers of the response.
     */
    private array $headers = [];

    /**
     * Constructs a new Response instance.
     *
     * @param string $content The content of the response.
     * @param int    $status  The HTTP status code of the response (default is 200).
     * @param array  $headers The headers of the response (default is an empty array).
     */
    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->setBody($content);
        $this->setStatus($status);
        $this->setHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    public function setContentType(string $contentType): Response
    {
        $this->doSet($contentType, 'content-type');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBody(string $content): Response
    {
        $this->doSet($content, 'body');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHeader(string $name, string $value): Response
    {
        $this->doSet(['name' => $name, 'value' => $value], 'header');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setHeaders(array $headers): Response
    {
        $this->doSet($headers, 'headers');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): Response
    {
        $this->doSet($status, 'statusCode');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sendHeaders(): Response
    {
        if (headers_sent()) {
            return $this;
        }

        $request = IncomingRequest::createFromGlobals();

        header(sprintf('HTTP/%s %s %s', $request->getProtocolVersion() ?? '1.1', $this->statusCode, $this->statusCodes[$this->statusCode]), true, $this->statusCode);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sendBody(): Response
    {
        echo $this->content;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function send(): Response
    {
        $this->sendHeaders();
        $this->sendBody();

        return $this;
    }

    /**
     * Sets various properties of the response object.
     *
     * This method is used internally to set the content, status code, headers, and content type
     * of the response object. It performs validation to ensure that headers containing URLs
     * are valid before being set.
     *
     * @param mixed  $data The data to set (content, status code, headers, or content type).
     * @param string $type The type of data being set ('body', 'headers', 'header', 'statusCode', or 'content-type').
     *
     * @throws \InvalidArgumentException If the data is invalid (e.g., unsupported status code, invalid URL).
     */
    private function doSet(mixed $data, string $type): void
    {
        switch ($type) {
            case 'body':
                $this->content = $data;
                break;

            case 'headers':
                foreach ($this->headers as $name => $value) {
                    if ($this->isUrl($value) && !$this->urlValidate($value)) {
                        throw new \InvalidArgumentException("Header '{$name}' is not a valid URL: {$value}");
                    }
                }

                $this->headers = array_merge($this->headers, $data);
                break;

            case 'header':
                if ($this->isUrl($data['value']) && !$this->urlValidate($data['value'])) {
                    throw new \InvalidArgumentException("Header '{$data['name']}' is not a valid URL: {$data['value']}");
                }

                $this->headers[$data['name']] = $data['value'];
                break;

            case 'statusCode':
                if (!in_array($data, array_keys($this->statusCodes))) {
                    throw new \InvalidArgumentException("Unsupported status code: {$data}");
                }

                $this->statusCode = $data;
                break;

            case 'content-type':
                if (!in_array($data, $this->contentTypes)) {
                    throw new \InvalidArgumentException("Unsupported Content-Type: {$data}");
                }

                $this->setHeader('Content-Type', $data);
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function redirect(string $url, $status = 302): Response
    {
        $this->setHeader('Location', $url);

        $this->setStatus($status);

        return $this;
    }

    /**
     * Determines whether a given string appears to be a URL.
     *
     * This method checks if the provided string starts with the HTTP or HTTPS scheme.
     * It dynamically detects the scheme based on the incoming request.
     *
     * @param string $string The string to check.
     * @return bool True if the string appears to be a URL, false otherwise.
     */
    private function isUrl(string $string): bool
    {
        $request = IncomingRequest::createFromGlobals();

        $scheme = $request->getScheme();

        if ($scheme !== 'http') {
            $patten = '/^http?:\/\//i';
        } else {
            $patten = '/^https?:\/\//i';
        }

        if (preg_match($patten, $string)) {
            return true;
        }

        return false;
    }

    /**
     * Validates a URL.
     *
     * This method uses PHP's filter_var function to validate the provided URL.
     *
     * @param string $url The URL to validate.
     * @return bool True if the URL is valid, false otherwise.
     */
    private function urlValidate(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return true;
        }

        return false;
    }
}
