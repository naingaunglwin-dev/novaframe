<?php

if (!function_exists('view')) {
    /**
     * Render a view template with optional data.
     *
     * @param string|array $file View file path or array of paths.
     * @param array $data Data to pass to the view.
     * @return string Rendered view content.
     */
    function view(array|string $file, array $data = []): string
    {
        return (new \NovaFrame\View\Renderer())->render($file, $data);
    }
}

if (!function_exists('render404')) {
    /**
     * Generate a 404 HTTP response with a 404 error view.
     *
     * @return \NovaFrame\Http\Response
     */
    function render404()
    {
        $response = new \NovaFrame\Http\Response();

        $response->setStatusCode(404);
        $response->setContent(view("errors.production.404"));

        return $response;
    }
}
