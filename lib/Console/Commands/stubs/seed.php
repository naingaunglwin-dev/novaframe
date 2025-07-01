<?php

return <<<EOT

<?php

namespace %s;

use NovaFrame\Database\Database;
use NovaFrame\Database\Seeder;

class %s extends Seeder
{
    public function run(): void
    {
        Database::table('users')->insert([
            'name'     => 'david',
            'email'    => 'david@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
        ]);
    }
}

EOT;

