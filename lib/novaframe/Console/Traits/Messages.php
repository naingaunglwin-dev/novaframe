<?php

namespace Nova\Console\Traits;

use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

trait Messages
{
    /**
     * Asks the user a question and returns the answer.
     *
     * @param string $question The question to ask.
     * @param bool|float|int|string|null $default The default value for the answer (default: false).
     * @param array $options Additional options for configuring the question (default: []).
     *        Available options:
     *        - 'multiline' (bool): Whether the question can accept multiline input (default: false).
     *        - 'trimmable' (bool): Whether the input should be trimmed (default: false).
     *        - 'hidden' (bool): Whether the input should be hidden (e.g., for passwords) (default: false).
     * @return mixed The user's answer to the question.
     */
    public function ask(string $question, bool|float|int|null|string $default = false, array $options = []): mixed
    {
        $options = $this->optionMerge('ask', $options);

        $helper = $this->getHelper('question');

        $question = !empty($default) ? "\n$question [<comment>" . $default . "</comment>]\n" : "\n$question\n";
        $question = new Question($question . '> ', $default);

        $this->setQuestionOptions($question, $options);

        $answer = $helper->ask($this->input, $this->output, $question);

        return $answer;
    }

    /**
     * Asks the user a yes/no question and returns the answer.
     *
     * @param string $question The question to ask.
     * @param bool $default The default answer (default: false).
     * @param array $options Additional options for configuring the question (default: []).
     *        Available options:
     *        - 'multiline' (bool): Whether the question can accept multiline input (default: false).
     *        - 'trimmable' (bool): Whether the input should be trimmed (default: false).
     *        - 'hidden' (bool): Whether the input should be hidden (e.g., for passwords) (default: false).
     * @return mixed The user's answer to the yes/no question.
     */
    public function confirm(string $question, bool $default = false, array $options = []): mixed
    {
        $options = $this->optionMerge('confirm', $options);

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("\n<options=bold>$question (yes/no) [<fg=yellow;options=bold>" . ($default === true ? 'yes' : 'no') . "</>]:\n > </>", $default);

        $this->setQuestionOptions($question, $options);

        $answer = $helper->ask($this->input, $this->output, $question);

        if ($answer) {
            $this->writeln('');
        }

        return $answer;
    }

    /**
     * Asks the user to choose an option from a list and returns the answer.
     *
     * @param string $question The question to ask.
     * @param array $choices The list of choices.
     * @param mixed|null $default The default choice (default: null).
     * @param array $options Additional options for configuring the question (default: []).
     *        Available options:
     *        - 'multiline' (bool): Whether the question can accept multiline input (default: false).
     *        - 'trimmable' (bool): Whether the input should be trimmed (default: false).
     *        - 'hidden' (bool): Whether the input should be hidden (e.g., for passwords) (default: false).
     *        - 'errorMessage' (string): The error message to display for invalid choices (default: '%s is invalid').
     *        - 'multiselect' (bool): Whether the user can select multiple choices (default: false).
     * @return mixed The user's answer to the question.
     */
    public function choice(string $question, array $choices, mixed $default = null, array $options = []): mixed
    {
        $options = $this->optionMerge('choice', $options);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion($question, $choices, $default);

        $this->setQuestionOptions($question, $options, 'choice');

        $answer = $helper->ask($this->input, $this->output, $question);

        $this->writeln('');

        return $answer;
    }

    /**
     * Displays a message within a colored box.
     *
     * @param string $message The message to display.
     * @param string $foreground The foreground color of the box (default: 'white').
     * @param string $background The background color of the box (default: 'green').
     * @param array $options Additional options for the box styling.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function box(string $message, string $foreground = 'white', string $background = 'green', array $options = [], bool $newline = false): void
    {
        $option = implode(',', $options);

        $this->write("<fg={$foreground};bg={$background};options={$option}> {$message} </> ", $newline);
    }

    /**
     * Displays a colored message.
     *
     * @param string $message The message to display.
     * @param string $color The color of the message (default: 'white').
     * @param array $options Additional options for styling the message.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function message(string $message, string $color = 'white', array $options = [], bool $newline = false): void
    {
        $option = implode(',', $options);

        $this->write("<fg={$color};options={$option}>$message</>", $newline);
    }

    /**
     * Writes output to the console.
     *
     * @param iterable|string $messages The message(s) to write.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @param int $options The output options (default: self::OUTPUT_NORMAL).
     * @return void
     */
    public function write(iterable|string $messages, bool $newline = false, int $options = self::OUTPUT_NORMAL): void
    {
        $this->output->write($messages, $newline, $options);
    }

    /**
     * Writes output to the console followed by a newline.
     *
     * @param iterable|string $messages The message(s) to write.
     * @param int $options The output options (default: self::OUTPUT_NORMAL).
     * @return void
     */
    public function writeln(iterable|string $messages, int $options = self::OUTPUT_NORMAL): void
    {
        $this->output->writeln($messages, $options);
    }

    /**
     * Displays an error message in red color.
     *
     * @param string $message The error message to display.
     * @param array $options Additional options for styling the message.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function error(string $message, array $options = [], bool $newline = false): void
    {
        $this->message($message, 'red', $options, $newline);
    }

    /**
     * Displays a success message in green color.
     *
     * @param string $message The success message to display.
     * @param array $options Additional options for styling the message.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function success(string $message, array $options = [], bool $newline = false): void
    {
        $this->message($message, 'green', $options, $newline);
    }

    /**
     * Displays a warning message in yellow color.
     *
     * @param string $message The warning message to display.
     * @param array $options Additional options for styling the message.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function warning(string $message, array $options = [], bool $newline = false): void
    {
        $this->message($message, 'yellow', $options, $newline);
    }

    /**
     * Displays a comment message.
     *
     * @param string $message The comment message to display.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function comment(string $message, bool $newline = false): void
    {
        $this->write("<comment>{$message}</comment>", $newline);
    }

    /**
     * Displays an info message in blue color.
     *
     * @param string $message The info message to display.
     * @param bool $newline Whether to add a newline after the message (default: false).
     * @return void
     */
    public function info(string $message, bool $newline = false): void
    {
        $this->write("<info>$message</info>");
    }

    /**
     * Merge the provided options with the default options for a specific type of question.
     *
     * @param string $on The type of question ('ask', 'confirm', 'choice').
     * @param array $options The provided options.
     * @return array The merged options.
     */
    private function optionMerge(string $on, array $options = [])
    {
        $defaultOptions = [
            'multiline'    => false,
            'trimmable'    => false,
            'hidden'       => false,
        ];

        if ($on === 'choice') {
            $defaultOptions['errorMessage'] = '%s is invalid';
            $defaultOptions['multiselect']  = false;
        }

        return array_merge($defaultOptions, $options);
    }

    /**
     * Set options for configuring a question.
     *
     * @param ChoiceQuestion|ConfirmationQuestion|Question $question The question object to configure.
     * @param array $options Additional options for configuring the question.
     *        Available options:
     *        - 'multiline' (bool): Whether the question can accept multiline input (default: false).
     *        - 'trimmable' (bool): Whether the input should be trimmed (default: false).
     *        - 'hidden' (bool): Whether the input should be hidden (e.g., for passwords) (default: false).
     *        - 'errorMessage' (string|null): The error message to display for invalid choices (default: null).
     *        - 'multiselect' (bool): Whether the user can select multiple choices (default: false).
     * @param string|null $on The type of question ('choice' or null).
     * @return void
     */
    private function setQuestionOptions(ConfirmationQuestion|ChoiceQuestion|Question $question, array $options, string $on = null): void
    {
        if ($on === 'choice') {
            $question->setErrorMessage($options['errorMessage']);
            $question->setMultiselect($options['multiselect']);
        }

        $question->setMultiline($options['multiline']);
        $question->setTrimmable($options['trimmable']);
        $question->setHidden($options['hidden']);
        $question->setHiddenFallback($options['hidden']);
    }
}
