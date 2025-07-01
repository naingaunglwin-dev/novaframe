<?php

return <<<EOT

<?php

namespace %s;

use NovaFrame\Controller;

class %s extends Controller
{
    public function init(\NovaFrame\Http\Request $request, \NovaFrame\Http\Response $response)
    {
        // initialize controller logic: serve as a filter for controller
        // can be safely removed if you don't want to use
    }

    // Your code goes here
}

EOT;
