<?php

namespace Nova\HTTP;

interface ResponseInterface
{
    /**
     * Sets the Content-Type header of the response.
     *
     * @param string $contentType The content type to set.
     * @return Response
     */
    public function setContentType(string $contentType): Response;

    /**
     * Sets the body content of the response.
     *
     * @param string $content The content to set.
     * @return Response
     */
    public function setBody(string $content): Response;

    /**
     * Sets a header of the response.
     *
     * @param string $name  The name of the header.
     * @param string $value The value of the header.
     * @return Response
     */
    public function setHeader(string $name, string $value): Response;

    /**
     * Sets multiple headers of the response.
     *
     * @param array $headers The headers to set (associative array of name-value pairs).
     * @return Response
     */
    public function setHeaders(array $headers): Response;

    /**
     * Sets the HTTP status code of the response.
     *
     * @param int $status The status code to set.
     * @return Response
     */
    public function setStatus(int $status): Response;

    /**
     * Sends the headers of the response.
     *
     * @return Response
     */
    public function sendHeaders(): Response;

    /**
     * Sends the body content of the response.
     *
     * @return Response
     */
    public function sendBody(): Response;

    /**
     * Sends the complete response (headers and body) to the client.
     *
     * @return Response
     */
    public function send(): Response;

    /**
     * Redirect to the given url
     *
     * @param string $url
     * @param int    $status
     * @return Response
     */
    public function redirect(string $url, int $status = 302): Response;
}
