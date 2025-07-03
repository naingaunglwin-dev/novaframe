<?php

namespace NovaFrame\Route;

final class RouteLoader
{
    /**
     * Path to the cached route file.
     *
     * @var string
     */
    const CACHE_FILE = DIR_BOOTSTRAP . 'cache' . DS . 'route.php';

    /**
     * Load routes into the provided RouteCollection.
     *
     * - If a cached route file exists, it loads the precompiled route definitions.
     * - If no cache is found, it loads the application-defined route file (e.g., `app/Routes/app.php`).
     *
     * @param RouteCollection $collection The route collection instance to populate.
     * @return void
     */
    public static function load(RouteCollection $collection): void
    {
        if (file_exists(static::CACHE_FILE)) {
            $collection->saveCached(require RouteLoader::CACHE_FILE);
        } else {
            require DIR_APP . 'Routes' . DS . 'app.php';
        }
    }
}
