<?php

namespace NovaFrame\Log;

use NovaFrame\Facade\Config;
use NovaFrame\Helpers\FileSystem\FileSystem;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\Log\Exceptions\InvalidLogLevel;
use NovaFrame\Log\Exceptions\PathNotFound;

class Log
{
    /**
     * Directory path where log files are stored.
     * 
     * @var string
     */
    private string $path;

    /**
     * Flag indicating if logging is enabled.
     * 
     * @var bool
     */
    private bool $shouldLog;

    /**
     * Minimum log level to record logs.
     * 
     * @var string
     */
    private string $logLevel;

    /**
     * Log filename (without extension).
     * 
     * @var string
     */
    private string $filename;

    /**
     * Supported log levels in ascending order of severity.
     */
    const LEVELS = ['DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'];

    /**
     * Log constructor.
     *
     * Loads configuration and initializes logging path, level, and filename.
     * Creates log directory if it does not exist.
     *
     * @param string|null $path Optional custom log directory path.
     */
    public function __construct(?string $path = null)
    {
        $config = Config::get('log');

        $this->path = $path ?? $config['path'];
        $this->shouldLog = $config['shouldLog'] ?? false;
        $this->filename = $config['filename'] ?? date('Y-m-d');

        $this->setLevel($config['logLevel'][app()->environment()] ?? 'debug');

        $this->resolvePath();
    }

    /**
     * Ensure the log directory path exists and is normalized.
     *
     * @return void
     */
    private function resolvePath(): void
    {
        $this->path = rtrim($this->path, '/');

        $this->path = Path::normalize($this->path);
        $this->path .= DIRECTORY_SEPARATOR;

        FileSystem::mkdir($this->path, 0777, true);
    }

    /**
     * Write a message to the log file with specified level.
     *
     * @param string $message Log message to write.
     * @param string $level Log level (default: 'debug').
     * @param bool $format Whether to prepend timestamp and level to message (default: true).
     * @param bool $force Whether to bypass the shouldLog flag (default: false).
     * @return void
     */
    public function write(string $message, string $level = 'debug', bool $format = true, bool $force = false): void
    {
        if (!$this->shouldLog && !$force) {
            return;
        }

        $this->doWrite($message, $level, $format);
    }

    /**
     * Write a debug level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function debug(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'debug', $format, $force);
    }

    /**
     * Write an info level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function info(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'info', $format, $force);
    }

    /**
     * Write a notice level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function notice(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'notice', $format, $force);
    }

    /**
     * Write a warning level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function warning(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'warning', $format, $force);
    }

    /**
     * Write an error level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function error(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'error', $format, $force);
    }

    /**
     * Write a critical level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function critical(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'critical', $format, $force);
    }

    /**
     * Write an alert level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function alert(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'alert', $format, $force);
    }

    /**
     * Write an emergency level message.
     *
     * @param string $message
     * @param bool $format
     * @param bool $force
     */
    public function emergency(string $message, bool $format = true, bool $force = false): void
    {
        $this->write($message, 'emergency', $format, $force);
    }

    /**
     * Internal method to write the log entry after level validation.
     *
     * @param string $message
     * @param string $level
     * @param bool $format
     * @return void
     */
    private function doWrite(string $message, string $level = 'debug', bool $format = true): void
    {
        $level = strtoupper($level);

        if (!$this->canLog($level)) {
            return;
        }

        if ($format) {
            $message = sprintf("%s [%s] %s\n", date('Y-m-d H:i:s'), $level, $message);
        }

        file_put_contents(Path::join($this->path, $this->filename . '.log'), "\n$message", FILE_APPEND);
    }

    /**
     * Read the contents of a log file.
     *
     * @param string $filename Filename of the log file to read.
     * @param bool $json Decode the content as JSON if true (default: false).
     * @return mixed|string|null Returns decoded JSON array or raw content string.
     *
     * @throws PathNotFound If the file does not exist.
     */
    public function read(string $filename, bool $json = false)
    {
        $file = Path::join($this->path, $filename);

        if (!file_exists($file)) {
            throw new PathNotFound($file);
        }

        $content = file_get_contents($file);

        if ($json) {
            return json_decode($content, true);
        }

        return $content;
    }

    /**
     * Clear (empty) a log file.
     *
     * @param string|null $filename Optional filename. Defaults to current log filename.
     * @return void
     */
    public function clear(?string $filename = null): void
    {
        $file = Path::join($this->path, ($filename ?? $this->filename) . '.log');

        if (file_exists($file)) {
            file_put_contents($file, '');
        }
    }

    /**
     * List all log files in the log directory.
     *
     * @return string[] Array of log filenames ending with '.log'.
     */
    public function listFiles(): array
    {
        return array_values(array_filter(scandir($this->path), fn($file) => str_ends_with($file, '.log')));
    }

    /**
     * Delete a specific log file.
     *
     * @param string $filename Filename of the log file to delete.
     *
     * @throws PathNotFound If the file does not exist.
     * @return void
     */
    public function delete(string $filename): void
    {
        $file = Path::join($this->path, $filename);

        if (!file_exists($file)) {
            throw new PathNotFound($file);
        }

        unlink($file);
    }

    /**
     * Create a new Log instance targeting a specific log file channel.
     *
     * @param string $name Name of the channel (prefix for filename).
     * @return self
     */
    public function channel(string $name): self
    {
        $clone = clone $this;
        $clone->filename = $name . '_' . date('Y-m-d_H-i-s');
        return $clone;
    }

    /**
     * Check if a message with given level should be logged based on current log level.
     *
     * @param string $level Log level to check.
     * @return bool True if message should be logged, false otherwise.
     */
    private function canLog(string $level): bool
    {
        $currentLvlIndex = array_search($this->logLevel, self::LEVELS);
        $messageLvlIndex = array_search(strtoupper($level), self::LEVELS);

        return $messageLvlIndex >= $currentLvlIndex;
    }

    /**
     * Set the minimum log level to record.
     *
     * @param string $level Log level (e.g., 'DEBUG', 'ERROR').
     *
     * @throws InvalidLogLevel If the provided level is invalid.
     * @return void
     */
    public function setLevel(string $level): void
    {
        $level = strtoupper($level);

        if (!in_array($level, self::LEVELS)) {
            throw new InvalidLogLevel($level);
        }

        $this->logLevel = $level;
    }
}
