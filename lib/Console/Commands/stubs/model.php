<?php

return <<<EOT

<?php

namespace %s;

use NovaFrame\Model;

// Uncomment if soft deletes are needed
// use NovaFrame\Database\SoftDelete;

class %s extends Model
{
    // Uncomment to enable soft delete functionality
    //use SoftDelete;

    protected string $table = '%s';

    protected array $fields = [
        // e.g. 'id', 'name', 'email'
    ];

    protected array $hidden = [
        // e.g. 'password'
    ];
}

EOT;
