<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channels
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | Query to the logs. The name specified in this option should match
    | one of the channels defined in the `config/logging.php` "channels" configuration array.
    |
    */

    'log_chhanels' =>  env('QUERY_LOG_CHHANELS', null),

    /*
    |--------------------------------------------------------------------------
    | To disable or enable the general query log.
    |--------------------------------------------------------------------------
    |
    | This option use to disable or enable the general query log,
    | Here you may configure the log channels for query log. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    */

    'query_log_enable' => env('QUERY_LOG_ENABLE', true),

];