<?php

return <<<EOT

<?php

namespace %s;

use Nova\Foundation\Model;

class %s extends Model
{
    /**
     * Table Name
     *
     * @var string
     */
    protected string $table = '%s';

    /**
     * Allowed Fields
     *
     * @var array
     */
    protected array $fields = [];
}

EOT;
