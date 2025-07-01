<?php

namespace NovaFrame\Database;

class MigrationBuilder
{
    private array $config;

    private string $driver;

    public function __construct(
        private string $table,
        private array $statements,
        private bool $dropTable
    )
    {
        $config = config('database');

        $this->driver = $config['default'];

        $this->config = $config['connections'][$this->driver];
    }

    public function toSql()
    {
        $dropForeignKeys = [];

        $dropTable = '';

        if (isset($this->statements['__drop_foreign_key']) && !empty($this->statements['__drop_foreign_key'])) {
            foreach ($this->statements['__drop_foreign_key'] as $foreignKey) {
                $dropForeignKeys[] = "ALTER TABLE " . $this->wrap($this->table) . " DROP FOREIGN KEY " . $this->wrap($foreignKey) . ";";
            }

            unset($this->statements['__drop_foreign_key']);
        }

        if ($this->dropTable) {
            $dropTable = "DROP TABLE IF EXISTS " . $this->wrap($this->table) . "; ";
        }

        $columns = [];
        $constraints = [];
        $alterQuery = [];

        foreach ($this->statements as $name => $props) {
            if (!empty($props['drop'])) {
                $alterQuery[] = "ALTER TABLE " . $this->wrap($this->table) . " DROP COLUMN IF EXISTS " . $this->wrap($name) . ";";
                continue;
            }

            $sql = $this->wrap($name);

            $type = strtoupper($props['type'] ?? 'VARCHAR');
            $constraint = $props['constraint'] ?? null;

            $sql .= " $type" . ($constraint ? "($constraint)" : '');

            if (!empty($props['unsigned'])) {
                $sql .= " UNSIGNED";
            }

            if (!empty($props['null'])) {
                $sql .= " NULL";
            } else {
                $sql .= " NOT NULL";
            }

            if (isset($props['default'])) {
                $default = is_string($props['default']) ? "'" . addslashes($props['default']) . "'" : $props['default'];
                $sql .= " DEFAULT $default";
            }

            if (!empty($props['auto_increment']) && $this->driver === 'mysql') {
                $sql .= " AUTO_INCREMENT";
            }

            if (!empty($props['unique'])) {
                $sql .= " UNIQUE";
            }

            if (!empty($props['comment'])) {
                $sql .= " COMMENT '{$props['comment']}'";
            }

            if (!empty($props['after']) && $this->driver === 'mysql') {
                $sql .= " AFTER " . $this->wrap($props['after']);
            }

            if (!empty($props['primary'])) {
                $constraints[] = "PRIMARY KEY ({$this->wrap($name)})";
            }

            $columns[] = $sql;

            if (!empty($props['foreign_key'])) {
                $fk = $props['foreign_key'];
                $onUpdate = $fk['onUpdate'] ? " ON UPDATE {$fk['onUpdate']}" : '';
                $onDelete = $fk['onDelete'] ? " ON DELETE {$fk['onDelete']}" : '';

                $constraints[] = "CONSTRAINT " . $this->wrap($this->table . '_' . $name . '_foreign') . " FOREIGN KEY (" . $this->wrap($name) . ") REFERENCES " . $this->wrap($fk['table']) . "(" . $this->wrap($fk['column']) . "){$onUpdate}{$onDelete}";
            }
        }

        $query = $dropTable;

        if (!empty($columns)) {
            $query .= "CREATE TABLE IF NOT EXISTS " . $this->wrap($this->table) . " (\n  " . implode(",\n  ", array_merge($columns, $constraints)) . "\n)";
        }

        if ($this->driver === 'mysql' && !empty($columns)) {
            $query .= " ENGINE={$this->config['engine']} DEFAULT CHARSET={$this->config['charset']};";
        }

        if (!empty($alterQuery)) {
            $query .= implode(";\n", $alterQuery);
        }

        if (!empty($dropForeignKeys)) {
            $query .= "\n" . implode("\n", $dropForeignKeys);
        }

        return $query;
    }

    private function wrap(string $identifier): string
    {
        return match ($this->driver) {
            'pgsql' => "\"{$identifier}\"",
            default => "`{$identifier}`"
        };
    }
}
