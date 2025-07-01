<?php

use NovaFrame\Helpers\Path\Path;

return (new \NovaFrame\Bootstrap())
    //->before() # add logic here to resolve before export
    //->autoload() # autoload files by framework | [Recommended] use composer autoload for better performance
    //->exception([ # setup global exception handling
    //    new class implements \NovaFrame\Exception\HandlerInterface {
    //        public function handle(Throwable $e): void
    //        {
    //            echo $e->getMessage();
    //        }
    //    }
    //])
    ->fallbackExceptionView(Path::join(DIR_APP, 'Views', 'errors', 'production', '404.php'))
    ->export(); # export application kernel after bootstrapping
