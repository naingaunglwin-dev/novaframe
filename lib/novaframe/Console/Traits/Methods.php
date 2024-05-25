<?php

namespace Nova\Console\Traits;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;

trait Methods
{
    /**
     * Get the value of the specified command argument.
     *
     * @param string $name The name of the argument.
     * @return mixed The value of the specified argument.
     */
    protected function getArgument(string $name): mixed
    {
        return $this->input->getArgument($name);
    }

    /**
     * Get all the command arguments as an associative array.
     *
     * @return array An associative array of command arguments.
     */
    protected function getArguments(): array
    {
        return $this->input->getArguments();
    }

    /**
     * Get the value of the first command argument.
     *
     * @return string|null The value of the first argument, or null if no arguments are present.
     */
    protected function getFirstArgument(): ?string
    {
        return $this->input->getFirstArgument();
    }

    /**
     * Determine if the specified command argument exists.
     *
     * @param string $name The name of the argument.
     * @return bool True if the argument exists, false otherwise.
     */
    protected function hasArgument(string $name): bool
    {
        return $this->input->hasArgument($name);
    }

    /**
     * Get the value of the specified command option.
     *
     * @param string $name The name of the option.
     * @return mixed The value of the specified option.
     */
    protected function getOption(string $name): mixed
    {
        return $this->input->getOption($name);
    }

    /**
     * Get all the command options as an associative array.
     *
     * @return array An associative array of command options.
     */
    protected function getOptions(): array
    {
        return $this->input->getOptions();
    }

    /**
     * Determine if the specified command option exists.
     *
     * @param string $name The name of the option.
     * @return bool True if the option exists, false otherwise.
     */
    protected function hasOption(string $name): bool
    {
        return $this->input->hasOption($name);
    }

    /**
     * Escape a command token.
     *
     * @param string $token The token to escape.
     * @return string The escaped token.
     */
    protected function escapeToken(string $token): string
    {
        return $this->input->escapeToken($token);
    }

    /**
     * Get the output formatter instance.
     *
     * @return OutputFormatterInterface The output formatter instance.
     */
    protected function getFormatter(): OutputFormatterInterface
    {
        return $this->output->getFormatter();
    }

    /**
     * Set the output formatter.
     *
     * @param OutputFormatterInterface $formatter
     * @return void
     */
    protected function setFormatter(OutputFormatterInterface $formatter): void
    {
        $this->output->setFormatter($formatter);
    }

    /**
     * Get the verbosity level of the output.
     *
     * @return int The verbosity level (one of the VERBOSITY_* constants).
     */
    protected function getVerbosity(): int
    {
        return $this->output->getVerbosity();
    }

    /**
     * Set the verbosity level of the output.
     *
     * @param int $level The new verbosity level (one of the VERBOSITY_* constants).
     * @return void
     */
    protected function setVerbosity(int $level): void
    {
        $this->output->setVerbosity($level);
    }
}
