<?php

namespace Nova\Console\DefaultCommands;

use Nova\Console\Command;
use Nova\Route\RouteDispatcher;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableStyle;

class DisplayDefinedRoutes extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected string $name = 'routes';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected string $description = 'Display defined routes list';

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
     *                      'shortcut'    => 's',
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
    protected string $usage = 'routes';

    /**
     * Command Action
     *
     * @return int
     */
    public function action(): int
    {
        $this->required('web');

        $dispatcher = RouteDispatcher::getInstance();
        $collection = $dispatcher->getRoutes();

        $this->table($collection, $dispatcher);

        return self::SUCCESS;
    }

    /**
     * Include the route file
     *
     * @param string $route
     * @return void
     */
    private function required(string $route): void
    {
        require_once APP_PATH . "Routes/{$route}.php";
    }

    /**
     * Display the defined routes with table
     *
     * @param $routes
     * @param $dispatcher
     * @return void
     */
    private function table($routes, $dispatcher): void
    {
        $table = new Table($this->output);

        $table->setHeaders(['No', 'Method', 'Route', 'Param', 'Param Rule', 'Handler', 'Handler Type', 'Route Name']);

        $no = 1;
        $rows = [];

        if (empty($routes)) {
            $table->addRow([new TableCell('<fg=red;options=bold>Routes are not defined yet</>', ['colspan' => 8])]);
        } else {
            foreach ($routes as $method => $data) {
                foreach ($data as $from => $to) {
                    $param = '';
                    $paramRule = '';

                    if (str_contains($from, '{')) {
                        $paramRule = [];
                        $arrays = explode('{', $from);
                        unset($arrays[0]);

                        foreach ($arrays as $index => $string) {
                            $arrays[$index] = substr($string, 0, strripos($string, '}'));

                            $paramAndRule = explode(',', $arrays[$index]);
                            $param .= trim($paramAndRule[0]) . ', ';

                            if (count($paramAndRule) > 1) {
                                unset($paramAndRule[0]);

                                $paramRule[] = trim(implode(',', $paramAndRule));
                            } else {
                                $paramRule[] = ':any';
                            }
                        }

                        $param = trim($param);
                        $param = substr($param, 0, -1);
                        $paramRule = implode(', ', $paramRule);
                    }

                    if (is_array($to)) {
                        $handlerType  = 'Controller';
                        $handlerValue = implode('->', $to) . '()';
                    } elseif (is_object($to)) {
                        $handlerType  = 'Custom';
                        $handlerValue = '(callback function)';
                    } else {
                        $handlerType  = 'View';
                        $handlerValue = $to;
                    }

                    $formatter = new FormatterHelper();

                    if (empty($param) && empty($paramRule)) {
                        $paramRule = $param = $formatter->formatBlock('none', 'comment');
                    }

                    $name = $dispatcher->getRouteName($from) ?? $formatter->formatBlock('none', 'comment');

                    $rows[] = [$no, $method, $from, $param, $paramRule, $handlerValue, $handlerType, $name];

                    $no++;
                }
            }

            $table->setRows($rows);
        }

        $tableStyle = new TableStyle();
        $tableStyle->setPadType(STR_PAD_BOTH);

        $table->setStyle($tableStyle)->setStyle('box-double')->render();
    }
}
