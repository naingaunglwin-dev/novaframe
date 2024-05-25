<?php

return [

    /*
     |------------------------------------------------------------------------
     | View Paths
     |------------------------------------------------------------------------
     |
     | Your application's view paths,
     | for normal output views and for views to used in debug (exception)
     |
     */
    'paths' => [
        /*
         |----------------------------------------------------------------------------------------------
         | View
         |----------------------------------------------------------------------------------------------
         |
         | The path to the directory containing normal output views.
         | This directory contains the views used for rendering standard output in the application.
         |
         */
        'view'  => APP_PATH . 'Views',

        /*
         |------------------------------------------------------------------------------
         | Exception View
         |------------------------------------------------------------------------------
         |
         | The path to the directory containing exception views.
         | This directory contains the views used for rendering debug information,
         | such as exceptions, errors.
         |
         */
        'exception' => APP_PATH . 'Views' . DIRECTORY_SEPARATOR . 'nova_exception'
    ]

];
