<?php

return [

    /*
     |-----------------------------------------------------------------------------------------------------------
     | Timestamp format
     |-----------------------------------------------------------------------------------------------------------
     |
     | Timestamp format for generating migration file names.
     |
     | This constant defines the timestamp format used for generating migration file names.
     | It is typically used to create unique migration file names by appending a timestamp
     | to the filename, allowing for chronological sorting of migration files.
     |
     | The format specified here follows one of the allowed patterns:
     | - 'Y-m-d-H-i-s_' (e.g., '2023-09-06-15-30-00_')
     | - 'YmdHis_' (e.g., '20230906153000_')
     |
     | These formats represent timestamps in different styles, including dashes, underscores, and
     | various delimiters. You can choose the format that best suits your project's naming conventions.
     |
     */
    'timestamp' => 'YmdHis_',

    /*
     |---------------------------------------------------------------------------------
     | Migration Table Name
     |---------------------------------------------------------------------------------
     |
     | The name of the table used for tracking migrations in the database.
     |
     | This configuration allows you to specify the name of the table used by the
     | migration system to keep track of executed migrations. By default, the
     | table name is set to 'migration'.
     |
     */
    'table' => 'migration',

];
