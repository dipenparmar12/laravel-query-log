# Laravel Query Log

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dipenparmar12/laravel-query-log.svg?style=flat-square)](https://packagist.org/packages/dipenparmar12/laravel-query-log)
[![Total Downloads](https://img.shields.io/packagist/dt/dipenparmar12/laravel-query-log.svg?style=flat-square)](https://packagist.org/packages/dipenparmar12/laravel-query-log)

A Laravel package for log queries in user defined log channel. Your all queries will save in the log file, and you can view it anytime.

## Installation

Install the package via composer:

```bash
composer require dipenparmar12/laravel-query-log
```
> **Note**: Make sure this package used only in development environment, Otherwise may you face decreased performance in production. 


 Optionally, you can publish the config file of this package with this command:

 > php artisan vendor:publish --provider="Dipenparmar12\QueryLog\StoreQueryLogServiceProvider" --tag="config"

 The following config file will be published in config/querylog.php
 
 
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for query log.
    | This option defines the default log channel that gets used when writing
    | Query to the logs. The name specified in this option should match
    | one of the channels defined in the  `config/logging.php` "channels" configuration array.
    | You can define multiple channels seprated by comman (,) for query log.
    | 
    */

    'log_chhanels' =>  env('QUERY_LOG_CHHANELS', null),

    /*
    |--------------------------------------------------------------------------
    | To disable or enable the general query log.
    |--------------------------------------------------------------------------
    |
    | This option use to disable or enable the general query log,
    | Out of the box, Laravel uses the Monolog PHP logging library. 
    | This gives you a variety of powerful log handlers / formatters 
    | to utilize.
    |
    */

    'query_log_enable' => env('QUERY_LOG_ENABLE', true),

];
```

### Usage

If you want to disable query log completely. change the following in ```.env``` file
 
```dotenv
QUERY_LOG_ENABLE=false
``` 

If we want to use query logging in multiple log channels, we can archive it by following ```.env``` 

```dotenv
QUERY_LOG_CHHANELS='single,daily'
``` 
> **Note**: Make sure the name specified in this option should match one of the channels defined in the ```config/logging.php``` **channels** configuration array.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dipenparmar12@gmail.com instead of using the issue tracker.

## Credits

-   [Dipen Parmar](https://github.com/dipenparmar12)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
