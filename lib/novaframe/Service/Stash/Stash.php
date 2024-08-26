<?php

namespace Nova\Service\Stash;

use Nova\Helpers\Modules\Str;

class Stash
{
    /**
     * The data storage array
     *
     * @var array
     */
    private array $data = [];

    /**
     * The singleton instance of the Stash class
     *
     * @var Stash|null
     */
    private static ?Stash $instance;

    /**
     * Stash constructor
     */
    public function __construct()
    {
    }

    /**
     * Store a value in the data array.
     *
     * @param string $key The key to store the value under.
     * @param mixed $value The value to store.
     * @return Stash The current instance of the Stash class.
     */
    public function put(string $key, mixed $value): Stash
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Retrieve a value from the data array.
     *
     * @param string $key The key to retrieve the value from.
     * @return mixed The retrieved value, or null if not found.
     */
    public function get(string $key): mixed
    {
        $result = null;

        $this->traverse($key, function (&$current, $k) use (&$result) {
            $result = $current[$k] ?? null;
        });

        return $result;
    }

    /**
     * Check if a key exists in the data array.
     *
     * @param string $key The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Push a value onto an array at the specified key.
     *
     * <h4>Remind</h2>
     *
     * If the key already exists and the value is not an array, it will be converted
     * into an array with the existing value as its first element, before the new value
     * is pushed onto it.
     *
     * @param string $key The key to push the value onto.
     * @param mixed $value The value to push.
     */
    public function push(string $key, $value): void
    {
        $data = $value;

        if ($this->has($key)) {
            $data = $this->get($key);

            if (!is_array($data)) {
                $data = [$data];
            }

            array_push($data, $value);
        }

        $this->put($key, $data);
    }

    /**
     * Remove a key from the data array.
     *
     * @param string $key The key to remove.
     */
    public function remove(string $key): void
    {
        $this->traverse($key, function (&$current, $k) {
            unset($current[$k]);
        });
    }

    /**
     * Traverse the data array based on a dotted key path and apply a callback.
     *
     * @param string $key The dotted key path to traverse.
     * @param callable $callback The callback to apply at the end of traversal.
     */
    private function traverse(string $key, callable $callback): void
    {
        $keys = Str::dot2Array($key);

        if (empty($keys)) {
            return;
        }

        $current = &$this->data;

        foreach ($keys as $index => $k) {
            if (Str::equal2($k, "*")) {
                if (is_array($current)) {
                    foreach ($current as &$value) {
                        $this->traverse(
                            implode(".", array_slice($keys, $index + 1)),
                            $callback
                        );
                    }
                }

                return;
            }

            if (isset($current[$k])) {
                if ($k === end($keys)) {
                    $callback($current, $k);
                } else {
                    $current = &$current[$k];
                }
            } else return;
        }
    }

    /**
     * Get singleton instance of stash
     *
     * @return Stash
     */
    public static function getInstance(): Stash
    {
        if (empty(self::$instance)) {
            self::$instance = new Stash();
        }

        return self::$instance;
    }

    /**
     * Return a new instance of stash
     *
     * @return Stash
     */
    public function new(): Stash
    {
        return new Stash();
    }
}
