<?php

return <<<EOT

<?php

namespace %s;

use NovaFrame\Database\Migration;
use NovaFrame\Database\TableSchema;

return new class extends Migration
{
    public function up(): TableSchema|string
    {
        $table = new TableSchema('users');

        $table->id()->primary()->autoIncrement();
        $table->string('name', 100);
        $table->string('email', 100);
        $table->string('password');
        $table->timestamps();

        return $table;
    }

    public function down(): TableSchema|string
    {
        return (new TableSchema('users'))
            ->dropTable();
    }
};

EOT;
