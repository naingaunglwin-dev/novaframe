<?php

namespace Nova\Exception;

use Nova\Helpers\Modules\Str;

class Beautify
{
    /**
     * @var array $data The exception data used for message formatting.
     */
    private array $data = [];

    /**
     * Beautify constructor.
     *
     * @param array $data The exception data passed to the class.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns a formatted message string.
     *
     * Format - `%message% in %file% on line %line%`
     *
     * @return string The formatted exception message.
     */
    public function message(): string
    {
        return sprintf(
            "%s in %s on line %s",
            $this->getData('message'),
            $this->getData('fileName'),
            $this->getData('line')
        );
    }

    /**
     * Returns a formatted title string with severity.
     *
     * @return string The formatted title with severity.
     */
    public function title(): string
    {
        return sprintf(
            $this->severity(
                $this->getData('severity')
            ) . "%s", $this->getData('message')
        );
    }

    /**
     * Extracts and returns the error type from the message.
     *
     * @return string The extracted error type.
     */
    public function error(): string
    {
        return Str::toArray(":", $this->getData('message'))[0];
    }

    /**
     * Displays formatted error messages or trace information.
     *
     * @param string $key The key to retrieve data from the exception array.
     * @return void|null Outputs the formatted content or returns null.
     */
    public function display(string $key)
    {
        if ($this->getData($key) == null) {
            return null;
        }

        if ($key == 'messages') {
            echo "<div class='group'>";

            echo "<div class='title'><p>" . $this->getData('fileName') . " on line " . $this->getData('line') . "&nbsp;&nbsp;&nbsp;</p></div>";

            echo "<pre>";

            echo "<table>";
        }

        foreach ($this->getData($key) as $file => $data) {

            if ($key == 'messages') {

                echo "<tr class='code-line" . ($file == $this->getData('line') ? " code-error" : '') . "'>";

                $output = "<td class='code'>";

                $data = str_replace(" ", "&nbsp;", $data);

                $output .= "<code class='language-php'>$file&nbsp;&nbsp;$data</code></td>";

                echo $output;

                echo "</tr>";

            } else {
                foreach ($data as $line => $content) {
                    echo "<div class='group'><div class='title'><p>&nbsp;$file on line $line&nbsp;&nbsp;&nbsp;</p></div>";

                    echo "<pre>";

                    echo "<table>";

                    foreach ($content as $k => $msg) {

                        echo "<tr class='code-line" . ($k == $line ? " code-error" : '') . "'>";

                        $output = "<td class='code'>";

                        $msg = str_replace(" ", "&nbsp;", $msg);

                        $output .= "<code class='language-php'>$k&nbsp;&nbsp;$msg</code></td>";

                        echo $output;

                        echo "</tr>";

                    }

                    echo "</table>";

                    echo "</pre>";

                    echo "</div>";
                }
            }
        }

        if ($key == 'messages') {
            echo "</table>";

            echo "</pre>";

            echo "</div>";
        }
    }

    /**
     * Retrieves data from the exception array.
     *
     * @param string $key The key to retrieve data from.
     * @return mixed|null The data associated with the key or null if not found.
     */
    private function getData(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Returns the formatted severity level.
     *
     * @param string $severity The severity level of the exception.
     * @return string The formatted severity span.
     */
    private function severity(string $severity): string
    {
        return sprintf(
            "<span class='severity'>%s</span>", $severity
        );
    }
}
