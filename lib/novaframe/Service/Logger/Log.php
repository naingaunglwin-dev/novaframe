<?php

namespace Nova\Service\Logger;

class Log
{
    /**
     * The directory path where log files will be stored.
     *
     * @var string
     */
    private string $path;

    /**
     * Determines if logging is enabled.
     *
     * @var bool
     */
    private bool $shouldLog;

    /**
     * The name of the log file.
     *
     * @var string
     */
    private string $filename;

    /**
     * The current logging level.
     *
     * @var string
     */
    private string $level;

    /**
     * Supported log levels.
     */
    const LEVELS = ['DEBUG', 'INFO', 'WARNING', 'ERROR'];

    /**
     * Log constructor.
     *
     * @param string|null $path The directory path where log files will be stored. Uses default config if null.
     */
    public function __construct(string $path = null)
    {
        $config = config('log');

        $this->path      = $path ?? $config['path'];
        $this->shouldLog = $config['shouldLog'] ?? false;
        $this->filename  = $config['filename'] ?? date('Y-m-d');

        $this->setLevel($config['level'][config('app.environment')] ?? 'DEBUG');

        if (!str_ends_with($this->path, '/')) {
            $this->path = $this->path . '/';
        }

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Write a log message if logging is enabled.
     *
     * @param string $message The message to log.
     * @param string $level The severity level of the log message.
     * @param bool $format Whether to format the message with a timestamp and level.
     */
    public function write(string $message, string $level = 'DEBUG', bool $format = true): void
    {
        if ($this->shouldLog) {
            $this->doWrite($message, $level, true);
        }
    }

    /**
     * Forcefully write a log message regardless of the logging status.
     *
     * @param string $message The message to log.
     * @param string $level The severity level of the log message.
     * @param bool $format Whether to format the message with a timestamp and level.
     */
    public function fwrite(string $message, string $level = 'DEBUG', bool $format = true): void
    {
        $this->doWrite($message, $level, $format);
    }

    /**
     * Custom log method that can force logging or respect the logging status.
     *
     * @param string $message The message to log.
     * @param string $level The severity level of the log message.
     * @param bool $force Whether to force the log message regardless of the logging status.
     * @param bool $format Whether to format the message with a timestamp and level.
     */
    public function custom(string $message, string $level = 'DEBUG', bool $force = false, bool $format = true): void
    {
        if ($force || $this->shouldLog) {
            $this->doWrite($message, $level, $format);
        }
    }

    /**
     * Internal method to write the log message to the log file.
     *
     * @param string $message The message to log.
     * @param string $level The severity level of the log message.
     * @param bool $format Whether to format the message with a timestamp and level.
     */
    private function doWrite(string $message, string $level, bool $format): void
    {
        $level = strtoupper($level);

        if ($this->canLog($level)) {
            if ($format) {
                $message = sprintf("[%s] %s: %s\n", date('Y-m-d H:i:s'), $level, $message);
            }

            file_put_contents($this->path . $this->filename . ".log", "\n$message", FILE_APPEND);
        }
    }

    /**
     * Set the current logging level.
     *
     * @param string $level The logging level to set.
     * @throws \InvalidArgumentException if the logging level is invalid.
     */
    public function setLevel(string $level): void
    {
        $level = strtoupper($level);

        if (in_array($level, self::LEVELS)) {
            $this->level = $level;
        } else {
            throw new \InvalidArgumentException("Invalid logging level: $level");
        }
    }

    /**
     * Clear all log files in the logging directory.
     */
    public function clearLogs(): void
    {
        $logs = glob($this->path . "*.log");

        foreach ($logs as $log) {
            unlink($log);
        }
    }


    /**
     * Read the contents of a log file.
     *
     * @param string $filename The name of the log file to read.
     * @return string The contents of the log file.
     * @throws \RuntimeException if the log file is not found.
     */
    public function read(string $filename): string
    {
        $file = $this->path . $filename;

        if (!file_exists($file)) {
            throw new \RuntimeException("Log file not found: $file");
        }

        return file_get_contents($file);
    }

    /**
     * Determine if a message can be logged based on the current logging level.
     *
     * @param string $level The severity level of the log message.
     * @return bool True if the message can be logged, false otherwise.
     */
    private function canLog(string $level): bool
    {
        $currentLevelIndex = array_search($this->level, self::LEVELS);
        $messageLevelIndex = array_search(strtoupper($level), self::LEVELS);

        return $messageLevelIndex >= $currentLevelIndex;
    }

    /**
     * Check if a log file exists.
     *
     * @param string $file The name of the log file.
     * @return bool True if the log file exists, false otherwise.
     */
    private function logFileExists(string $file): bool
    {
        return file_exists($this->path . "{$file}.log");
    }
}
