<?php

namespace NovaFrame\Database;

/**
 * Class TableSchema
 *
 * Represents a database table schema builder.
 * Provides a fluent interface to define columns, constraints,
 * indexes, foreign keys, and table operations like drop.
 *
 * Example usage:
 * ```
 * $schema = (new TableSchema('users'))
 *     ->integer('id')->primary()->autoIncrement()
 *     ->string('name', 100)->null()
 *     ->timestamps()
 *     ->build();
 * ```
 */
class TableSchema
{
    /**
     * Current column being built.
     *
     * @var string
     */
    private string $column = '';

    /**
     * Statements describing the schema for each column.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $statements = [];

    /**
     * Whether the entire table should be dropped.
     *
     * @var bool
     */
    private bool $dropTable = false;

    /**
     * Table name.
     *
     * @var string|null
     */
    public function __construct(private ?string $table = null)
    {
    }

    /**
     * Set the table name.
     *
     * @param string $table
     * @return $this
     */
    public function table(string $table): TableSchema
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Start defining a column.
     *
     * @param string $column Column name
     * @return $this
     */
    public function column(string $column): TableSchema
    {
        $this->column = $column;
        $this->statements[$column] = [];

        return $this;
    }

    /**
     * Shortcut to define an integer 'id' column.
     *
     * @return $this
     */
    public function id(): TableSchema
    {
        return $this->integer('id');
    }

    /**
     * Set the data type of the current column.
     *
     * @param string $type Data type, e.g. 'VARCHAR', 'INT'
     * @return $this
     *
     * @throws \InvalidArgumentException If no column is selected
     */
    public function type(string $type): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['type'] = $type;

        return $this;
    }

    /**
     * Set the length/constraint of the current column.
     *
     * @param int $constraint
     * @return $this
     *
     * @throws \InvalidArgumentException If no column is selected
     */
    public function constraint(int $constraint): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['constraint'] = $constraint;

        return $this;
    }

    /**
     * Mark the current column as auto-incrementing.
     *
     * @param bool $autoIncrement
     * @return $this
     *
     * @throws \InvalidArgumentException If no column is selected
     */
    public function autoIncrement(bool $autoIncrement = true): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['auto_increment'] = $autoIncrement;

        return $this;
    }

    /**
     * Mark the current column as primary key.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function primary(): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['primary'] = true;

        return $this;
    }

    /**
     * Allow the current column to be NULL.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function null(): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['null'] = true;

        return $this;
    }

    /**
     * Specify that the current column should be placed after another column.
     *
     * @param string $column
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function after(string $column): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['after'] = $column;

        return $this;
    }

    /**
     * Set a default value for the current column.
     *
     * @param string|int|null $value
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function default(string|int|null $value = null): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['default'] = $value;

        return $this;
    }

    /**
     * Make the current column unique.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function unique(): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['unique'] = true;

        return $this;
    }

    /**
     * Add standard timestamp columns: created_at, updated_at, deleted_at (nullable).
     *
     * @return $this
     */
    public function timestamps(): TableSchema
    {
        $this->column('created_at')
            ->type('datetime')
            ->null();

        $this->column('updated_at')
            ->type('datetime')
            ->null();

        $this->column('deleted_at')
            ->type('datetime')
            ->null();

        return $this;
    }

    /**
     * Define a VARCHAR string column with optional length.
     *
     * @param string $column
     * @param int $length
     * @return $this
     */
    public function string(string $column, int $length = 255): TableSchema
    {
        return $this->column($column)
            ->type('VARCHAR')
            ->constraint($length);
    }

    /**
     * Define an INT integer column with optional length.
     *
     * @param string $column
     * @param int $length
     * @return $this
     */
    public function integer(string $column, int $length = 11): TableSchema
    {
        return $this->column($column)
            ->type('INT')
            ->constraint($length);
    }

    /**
     * Define a TEXT column.
     *
     * @param string $column
     * @return $this
     */
    public function text(string $column): TableSchema
    {
        return $this->column($column)
            ->type('TEXT');
    }

    /**
     * Mark a column for dropping.
     *
     * @param string $column
     * @return $this
     */
    public function dropColumn(string $column): TableSchema
    {
        $this->column = $column;

        $this->statements[$column]['drop'] = true;

        return $this;
    }

    /**
     * Mark the entire table for dropping.
     *
     * @return $this
     */
    public function dropTable(): TableSchema
    {
        $this->dropTable = true;

        return $this;
    }

    /**
     * Add a foreign key constraint to the current column.
     *
     * @param string $table Referenced table name
     * @param string $column Referenced column name
     * @param string|null $onUpdate Optional ON UPDATE action
     * @param string|null $onDelete Optional ON DELETE action
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function foreignKey(string $table, string $column, ?string $onUpdate = null, ?string $onDelete = null): TableSchema
    {
        $this->validateColumn();

        $this->statements[$this->column]['foreign_key'] = compact('table', 'column', 'onUpdate', 'onDelete');

        return $this;
    }

    /**
     * Drop a foreign key by key name.
     *
     * @param string $key Foreign key constraint name
     * @return $this
     */
    public function dropForeignKey(string $key): TableSchema
    {
        $this->statements['__drop_foreign_key'][] = $key;

        return $this;
    }

    /**
     * Build the migration commands.
     *
     * @return MigrationBuilder
     */
    public function build(): MigrationBuilder
    {
        return new MigrationBuilder(
            $this->table,
            $this->statements,
            $this->dropTable
        );
    }

    /**
     * Validate that a column has been selected for chaining.
     *
     * @throws \InvalidArgumentException If no column is currently selected.
     */
    private function validateColumn(): void
    {
        if (empty($this->column) || !isset($this->statements[$this->column])) {
            throw new \InvalidArgumentException('TableSchema::column() should be called first');
        }
    }
}
