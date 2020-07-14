<?php

namespace Dipenparmar12\QueryLog;

use DB;
use Dipenparmar12\QueryLog\Exceptions\LogChannelInvalidException;
use Log;
use Illuminate\Support\ServiceProvider;
use Throwable;

class StoreQueryLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/querylog_config.php' => config_path('querylog.php'),
            ], 'config');
        }

        if (config('querylog.query_log_enable') == true) {
            /// Log all queries executed, performed by Application
            DB::connection()->enableQueryLog();
            DB::listen(function ($query) {
                try {
                    Log::stack($this->get_log_channels())->info($this->BindQueryLog($query->sql, $query->bindings));
                } catch (\Exception $t) {
                    throw new LogChannelInvalidException('In given channels one or more channels are not defined.');
                }
            });
        }
    }

    /**
     * Get log channels
     * @return array
     */
    protected function get_log_channels()
    {
        return collect(array_filter(explode(',', config('querylog.log_chhanels'))))->toArray();
        # "one,two" => ['one', 'two'];
    }

    /**
     * Bind-Query parameters in Query string
     *
     * @param $sql
     * @param $binds
     *
     * @return string
     */
    protected function BindQueryLog($sql, $binds)
    {
        $result = "";
        $sql_chunks = explode('?', $sql);

        foreach ($sql_chunks as $key => $sql_chunk) {
            if (isset($binds[$key])) {
                $result .= $sql_chunk . '"' . $binds[$key] . '"';
            }
        }

        $result .= $sql_chunks[count($sql_chunks) - 1];
        return $result;
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/querylog_config.php', 'querylog');
    }

}