<?php

namespace Nova\HTTP;

class Request implements RequestInterface
{
    /**
     * Globals values
     *
     * @var object
     */
    protected object $globals;

    /**
     * Sanitized global values
     *
     * @var object
     */
    protected object $sanitized;

    public function __construct(
        array $get = null,
        array $post = null,
        array $cookie = null,
        array $files = null,
        array $server = null,
    )
    {
        $content = file_get_contents('php://input');

        $this->globals = (object)[
            'get'    => empty($get) ? $_GET : $get,
            'post'   => empty($post) ? $_POST : $post,
            'cookie' => empty($cookie) ? $_COOKIE : $cookie,
            'files'  => empty($files) ? $_FILES : $files,
            'server' => empty($server) ? $_SERVER : $server,
            'header' => php_sapi_name() !== 'cli' ? getallheaders() : [],
            'body'   => json_decode($content, true),
        ];

        $this->sanitize();
    }

    /**
     * @inheritDoc
     */
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * Sanitizes the request data.
     */
    protected function sanitize(): void
    {
        $this->sanitized = (object) [
            'get'    => $this->filter('GET'),
            'post'   => $this->filter('POST'),
            'file'   => $this->filter('FILES'),
            'cookie' => $this->filter('COOKIE'),
            'server' => $_SERVER,
            'header' => php_sapi_name() !== 'cli' ? [] : getallheaders(),
            'body'   => $this->filter('BODY'),
        ];
    }

    /**
     * Check if it is sanitized or not
     *
     * @return bool
     */
    protected function isSanitized(): bool
    {
        return !empty($this->sanitized);
    }

    /**
     * Filters and sanitizes the request data based on the specified method.
     *
     * @param string $method The HTTP method ('GET', 'POST', 'FILE', 'PATCH', 'DELETE', 'PUT').
     *
     * @return array The sanitized request data if invalid method provided.
     */
    private function filter(string $method): array
    {
        $sanitized = [];

        switch ($method) {
            case 'GET':

                $filtered = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS | FILTER_SANITIZE_ENCODED);

                if (empty($filtered)) {
                    return $sanitized;
                }

                $sanitized = $this->escapeData($filtered);

                break;
            case 'POST':

                $filtered = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS | FILTER_SANITIZE_ENCODED);

                if (empty($filtered)) {
                    return $sanitized;
                }

                $sanitized = $this->escapeData($filtered);

                break;

            case 'FILES':

                if (empty($this->globals->file)) {
                    return $sanitized;
                }

                foreach ($this->globals->file as $key => $file) {
                    if (in_array(pathinfo($file['name'], PATHINFO_EXTENSION), config('file.allowed_file_types'))) {
                        $sanitized[$key] = [
                            'name'     => htmlspecialchars($file['name']),
                            'type'     => $file['type'],
                            'size'     => $file['size'],
                            'tmp_name' => $file['tmp_name'],
                            'error'    => $file['error'],
                        ];
                    }
                }

                break;

            case 'COOKIE':

                $filtered = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_SPECIAL_CHARS | FILTER_SANITIZE_ENCODED);

                if (empty($filtered)) {
                    return $sanitized;
                }

                $sanitized = $this->escapeData($filtered);

                break;

            case 'BODY': // For PUT, PATCH, DELETE

                $data = file_get_contents('php://input');

                if (empty($data)) {
                    return $sanitized;
                }

                $data = json_decode($data, true);

                $sanitized = $this->escapeData($data);

                break;

            default:
                return $sanitized;
        }

        return $sanitized;
    }

    /**
     * Escape HTML special characters from given data
     *
     * @param $data
     * @return array
     */
    private function escapeData($data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->escapeHtmlInArray($value);
            } else {
                $result[$key] = htmlspecialchars($value);
            }
        }

        return $result;
    }

    /**
     * Escape HTML special characters from give array
     *
     * @param array $array
     * @return array
     */
    private function escapeHtmlInArray(array $array): array
    {
        $escaped = [];

        if (empty($array)) {
            return $escaped;
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $escaped[$key] = $this->escapeHtmlInArray($value);
            } else {
                $escaped[$key] = htmlspecialchars($value);
            }
        }

        return $escaped;
    }
}
