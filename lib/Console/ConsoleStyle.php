<?php

namespace NovaFrame\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleStyle
{
    /**
     * @var SymfonyStyle
     */
    protected SymfonyStyle $io;

    /**
     * @var InputInterface
     */
    protected InputInterface $input;

    protected OutputInterface $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->io = new SymfonyStyle($input, $output);
    }

    public function success(string $message, bool $newline = false, string $icon = '✓', int $padding = 1): void
    {
        $icon = $this->strPadToIcon($icon, 1);

        $this->message($icon . $message, 'green', newline: $newline, padding: $padding);
    }

    public function error(string $message, bool $newline = false, string $icon = '🞩', int $padding = 1): void
    {
        $icon = $this->strPadToIcon($icon, 1);

        $this->message( $icon . $message, 'red', newline: $newline, padding: $padding);
    }

    public function warning(string $message, bool $newline = false, string $icon = '⚠', int $padding = 1): void
    {
        $icon = $this->strPadToIcon($icon, 1);

        $this->message($icon . $message, 'yellow', newline: $newline, padding: $padding);
    }

    public function info(string $message, bool $newline = false, int $padding = 0): void
    {
        $this->message($message, 'cyan', newline: $newline, padding: $padding);
    }

    public function comment(string $message, bool $newline = false, int $padding = 0): void
    {
        $this->message($message, 'yellow', newline: $newline, padding: $padding);
    }

    public function text(string $message, bool $newline = false, string $icon = '', int $padding = 0): void
    {
        $icon = $this->strPadToIcon($icon, 1);

        $this->message( $icon . $message, 'default', newline: $newline, padding: $padding);
    }

    public function secondary(string $message, bool $newline = false, string $icon = '', int $padding = 0): void
    {
        $this->message($icon . $message, 'gray', newline: $newline, padding: $padding);
    }

    public function box(string $message, string $foreground = 'black', string $background = 'white', array $options = [], bool $newline = false, int $padding = 0): void
    {
        $this->message($message, $foreground, $background, $options, $newline, $padding);
    }

    public function ask(string $question, ?string $default = null): mixed
    {
        return  $this->io->ask($question, $default);
    }

    public function confirm(string $question, bool $default = true): bool
    {
        return $this->io->confirm($question, $default);
    }

    public function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed
    {
        return $this->io->choice($question, $choices, $default, $multiSelect);
    }

    public function note(string|array $message): void
    {
        $this->io->note($message);
    }

    public function write(iterable|string $message, bool $newline = false, int $options = 0): void
    {
        $this->output->write($message, $newline, $options);
    }

    public function writeln(iterable|string $message, int $options = 0): void
    {
        $this->output->writeln($message, $options);
    }

    public function message(string $message, ?string $foreground = null, ?string $background = null, array $options = [], bool $newline = false, int $padding = 0): void
    {
        $string = '<';

        if (!empty($foreground)) {
            $string .= 'fg=' . $foreground . ';';
        }

        if (!empty($background)) {
            $string .= 'bg=' . $background . ';';
        }

        if (!empty($options)) {
            $option = implode(',', $options);

            $string .= 'options=' . $option . ';';
        }

        $message = str_repeat(' ', $padding) . $message . str_repeat(' ', $padding);

        $string .= '>' . $message . '</>';

        $this->output->write($string, $newline);
    }

    public function table(array $headers, array $rows): void
    {
        $this->io->table($headers, $rows);
    }

    public function block(string|array $message, ?string $type = null, ?string $style = null, string $prefix = ' ', bool $padding = false, bool $escape = true): void
    {
        $this->io->block($message, $type, $style, $prefix, $padding, $escape);
    }

    public function newLine(int $count = 1): void
    {
        $this->io->newLine($count);
    }

    public function section(string $message): void
    {
        $this->io->section($message);
    }

    public function getSymfonyStyle(): SymfonyStyle
    {
        return $this->io;
    }

    private function strPadToIcon(string $icon = '', int $padding = 0): string
    {
        if ($icon === '') {
            return $icon;
        }

        return $icon . str_repeat(' ', $padding);
    }
}
