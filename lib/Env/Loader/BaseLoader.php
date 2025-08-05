<?php

namespace NovaFrame\Env\Loader;

use Nette\FileNotFoundException;
use NovaFrame\Env\Parser\ParserInterface;
use NovaFrame\Helpers\Path\Path;

abstract class BaseLoader implements LoaderInterface
{
    /**
     * The default filename to load if no file is specified.
     *
     * @var string
     */
    private string $defaultFile = '.env';

    /**
     * BaseLoader constructor
     *
     * @param string|array|null    $files    The file or files to load.
     * @param bool                 $override Whether to override existing env values.
     * @param ParserInterface|null $parser   Optional parser to parse file contents.
     */
    public function __construct(
        protected null|string|array $files = null,
        protected bool $override = false,
        protected ?ParserInterface $parser = null,
    )
    {
        $this->files = $files ?? $this->getDefaultFile();

        if (!is_array($this->files)) {
            $this->files = [$this->files];
        }
    }

    /**
     * Get the default filename to load.
     *
     * @return string
     */
    protected function getDefaultFile(): string
    {
        return $this->defaultFile;
    }

    /**
     * Resolves all configured file paths to absolute paths.
     *
     * @return string[] Array of resolved file paths.
     */
    protected function resolveFiles(): array
    {
        return array_map(function ($file) {
            $file = Path::normalize($file);

            if (file_exists($file)) {
                return $file;
            }

            $newPath = Path::join(DIR_ROOT, $file);

            if (!file_exists($newPath)) {
                throw new FileNotFoundException($newPath);
            }

            return $newPath;
        }, $this->files);
    }

    /**
     * Get the parser instance.
     *
     * @return ParserInterface|null
     */
    protected function parser(): ?ParserInterface
    {
        return $this->parser;
    }

    /**
     * Sets environment variables into PHP's superglobals and environment.
     *
     * @param array $envs Array with an 'envs' key holding key-value env pairs.
     */
    protected function save(array $envs): void
    {
        $envs = $envs['envs'] ?? [];

        if (!empty($envs)) {
            foreach ($envs as $key => $value) {
                putenv("$key=$value");
                $_ENV[$key]    = $value;
                $_SERVER[$key] = $value;
            }

            $this->syncServer($envs);
        }
    }

    /**
     * Syncs environment variables in $_SERVER and unsets removed ones.
     *
     * @param array $new Newly set environment variables.
     */
    protected function syncServer(array $new): void
    {
        $previous = $_SERVER['_nubo_dotenv_keys_'] ?? null;
        $keys = array_keys($new);

        if (null !== $previous) {
            $previous = explode(",", $previous);

            $diff = array_diff($new, $previous);

            if (!empty($diff)) {
                foreach ($diff as $key => $value) { // remove env vars if there is diff
                    putenv($key);
                    unset($_ENV[$key]);
                    unset($_SERVER[$key]);
                }
            }
        }

        $_SERVER['_nubo_dotenv_keys_'] = implode(",", $keys);
    }
}
