<?php

namespace NovaFrame\Database;

class MigrationTable
{
    private string $table;

    public function __construct(private Database $db)
    {
        $this->table = config('migration.table', 'migrations');
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function createTable()
    {
        $table = new TableSchema($this->table);

        $table->integer('id')
            ->primary()
            ->autoIncrement()
            ->constraint(11);

        $table->string('file');

        $table->integer('version');

        $table->timestamps();

        $sql = $table->build()->toSql();

        return $this->db->execute($sql);
    }

    public function save(string $file, int $version): void
    {
        $builder = Database::table($this->table);
        $builder->insert([
            'file' => $file,
            'version' => $version,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getLastVersion(): int|null
    {
        $builder = Database::table($this->table);

        $result = $builder->select('version')->order('version DESC')->limit(1)->fetch();

        return $result ? $result->version : null;
    }

    public function dropTable(): string
    {
        $table = new TableSchema($this->table);

        $table->dropTable();

        return $table->build()->toSql();
    }

    public function getMigrations(?int $version = null): array
    {
        $this->createTable();

        $builder = Database::table($this->table);

        $builder->select('file');

        if ($version !== null) {
            $builder->where('version', $version);
        }

        $migrations = $builder->fetchAll();

        if (empty($migrations)) {
            return [];
        }

        $result = [];

        foreach ($migrations as $migration) {
            $result[] = $migration['file'];
        }

        return $result;
    }

    public function delete(?string $file = null, ?int $version = null): void
    {
        $builder = Database::table($this->table);

        if ($file !== null) {
            $builder->where('file', $file);
        }

        if ($version !== null) {
            $builder->where('version', $version);
        }

        $builder->delete();
    }
}
