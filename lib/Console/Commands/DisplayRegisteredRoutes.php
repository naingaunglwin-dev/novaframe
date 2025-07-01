<?php

namespace NovaFrame\Console\Commands;

use NovaFrame\Console\Command;
use NovaFrame\Helpers\Path\Path;
use NovaFrame\Route\RouteCollection;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableStyle;

class DisplayRegisteredRoutes extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'route:list';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Display registered routes';

    /**
     * An array of command arguments.
     * Each argument is defined by an associative array with keys:
     * - 'name': The name of the argument.
     * - 'description': The description of the argument.
     * - 'mode': Argument mode
     * - Example : $arguments = [
     *                  [
     *                      'name'        => 'argName',
     *                      'description' => 'argDescription',
     *                      'mode'        => InputArgument::REQUIRED
     *                  ],
     *              ];
     *
     * @var array
     */
    protected array $arguments = [];

    /**
     * An array of command options.
     * Each option is defined by an associative array with keys:
     * - 'name': The name of the option.
     * - 'shortcut': The shortcut for the option.
     * - 'mode': Option mode
     * - 'description': The description of the option.
     * - 'default': default value
     * - Example : $arguments = [
     *                  [
     *                      'name'        => 'optionName',
     *                      'short'       => 's',
     *                      'mode'        => InputOption::VALUE_REQUIRED,
     *                      'description' => 'optionDescription',
     *                      'default'     => false
     *                  ],
     *              ];
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Usage for command
     * - Example : $usage = 'command:name [argument] [option]'
     *
     * @var string
     */
    protected string $usage = 'route:list';

    public function handle()
    {
        require Path::join(DIR_APP, 'Routes', 'app.php');

        /** @var RouteCollection $collection */
        $collection = app('routes');

        $this->table($collection->getRouteList());
    }

    private function table($lists): void
    {
        $table = new Table($this->output);

        $table->setHeaders([' no ', ' method ', ' route ', ' param ', ' handler ', ' action ', ' name ']);

        $no = 1;
        $rows = [];

        $formatter = new FormatterHelper();

        if (empty($lists)) {
            $table->addRow([new TableCell('<comment>not found.</comment>', ['colspan' => 7])]);
        } else {
            foreach ($lists as $method => $list) {
                foreach ($list as $route => $items) {
                    $param = '';

                    if (str_contains($route, '{')) {
                        $arrays = explode('{', $route);
                        unset($arrays[0]);

                        foreach ($arrays as $index => $string) {
                            $arrays[$index] = substr($string, 0, strripos($string, '}'));

                            $paramAndRule = explode(',', $arrays[$index]);
                            $param .= trim($paramAndRule[0]) . ', ';
                        }

                        $param = trim($param);
                        $param = substr($param, 0, -1);
                    }

                    if (is_array($items['action'])) {
                        $handlerType  = 'Controller';
                        $handlerValue = implode('->', $items['action']) . '()';
                    } elseif (is_object($items['action'])) {
                        $handlerType = $formatter->formatBlock('λ', 'fg=cyan');
                        $handlerValue = $formatter->formatBlock("⌈callback function⌋", 'info');
                    } else {
                        $handlerType  = 'View';
                        $handlerValue = $items['action'];

                        if (!pathinfo($handlerValue, PATHINFO_EXTENSION)) {
                            $handlerValue .= '.php';
                        }

                        $handlerValue = Path::join(DIR_APP, 'Views', $handlerValue);
                    }

                    if (empty($param)) {
                        $param = $formatter->formatBlock('none', 'comment');
                    }

                    $name = $items['name'] ?? $formatter->formatBlock('none', 'comment');

                    $rows[] = [$no, $method, $route, $param, $handlerValue, $handlerType, $name];

                    $no++;
                }
            }

            $table->setRows($rows);
        }

        $tableStyle = new TableStyle();
        $tableStyle->setPadType(STR_PAD_BOTH);

        $table->setStyle($tableStyle)->setStyle('default')->render();
    }
}
