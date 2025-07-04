<?php

namespace NovaFrame\Database;

use Nette\Caching\Storages\DevNullStorage;
use Nette\Database\Connection;
use Nette\Database\Explorer;
use Nette\Database\ResultSet;
use Nette\Database\Structure;

class Database
{
    /**
     * The raw database configuration array.
     *
     * @var array
     */
    private array $config;

    /**
     * The raw Nette database connection.
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * The Nette Explorer instance for fluent DB operations.
     *
     * @var Explorer
     */
    private Explorer $explorer;

    /**
     * The singleton instance of the Database.
     *
     * @var Database
     */
    private static Database $instance;

    /**
     * Constructor
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $driver = $config['driver'] ?? 'mysql';
        $connection = $config['connections'][$driver];

        $this->connection = new Connection(
            $this->buildDsn($connection),
            $connection['username'],
            $connection['password'],
        );

        $this->explorer = new Explorer(
            $this->connection,
            new Structure($this->connection, new DevNullStorage()),
        );
    }

    /**
     * Build a DSN string from the configuration array.
     *
     * @param array $config
     * @return string
     */
    private function buildDsn(array $config): string
    {
        $driver = $config['driver'] ?? 'mysql';
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 3306;
        $database = $config['database'] ?? '';

        return sprintf('%s:host=%s;port=%s;dbname=%s', $driver, $host, $port, $database);
    }

    /**
     * Get or create a singleton Database instance.
     *
     * @return static
     */
    public static function connect()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(config('database'));
        }

        return self::$instance;
    }

    /**
     * Get a fluent selection from the given table.
     *
     * @param string $table
     * @return \Nette\Database\Table\Selection
     */
    public static function table(string $table)
    {
        return Database::connect()->explorer()->table($table);
    }

    /**
     * Get the Nette Explorer instance.
     *
     * @return Explorer
     */
    public function explorer(): Explorer
    {
        return $this->explorer;
    }

    /**
     * Execute a raw SQL query.
     *
     * @param string $query
     * @return ResultSet
     */
    public function execute(string $query): ResultSet
    {
        $result = $this->connection->query($query);

        $result->getPdoStatement()?->closeCursor();

        return $result;
    }

    /**
     * Get the raw Nette Connection instance.
     *
     * @return Connection
     */
    public function connection(): Connection
    {
        return $this->connection;
    }

    /**
     * Begin a database transaction.
     *
     * @return void
     */
    public function begin(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit the current transaction.
     *
     * @return void
     */
    public function commit(): void
    {
        $this->connection->commit();
    }

    /**
     * Rollback the current transaction.
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->connection->rollBack();
    }

    /**
     * Execute a callback within a transaction.
     *
     * @param callable $callback The function to run within the transaction.
     * @return mixed The result of the callback.
     */
    public function transaction(callable $callback): mixed
    {
        return $this->connection->transaction(fn() => $callback($this));
    }

    /**
     * Get the last inserted ID.
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->connection->getInsertId();
    }

    /**
     * Quote a string for use in a query.
     *
     * @param string $string
     * @return string
     */
    public function quote(string $string): string
    {
        return $this->connection->quote($string);
    }

    /**
     * Get the current database configuration.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
