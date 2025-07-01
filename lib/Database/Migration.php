<?php

namespace NovaFrame\Database;

abstract class Migration
{
    /**
     * Apply the migration.
     *
     * @return TableSchema|string A TableSchema object for programmatic migrations
     *                            or a raw SQL string.
     */
    abstract public function up(): TableSchema|string;

    /**
     * Reverse the migration.
     *
     * @return TableSchema|string A TableSchema object or a raw SQL string.
     */
    abstract public function down(): TableSchema|string;
}
