<?php

namespace Dipenparmar12\QueryLog;

use DB;
use Dipenparmar12\QueryLog\Exceptions\QueryLogException;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class StoreQueryLogServiceProvider extends ServiceProvider
{
    public $configRepository;

    /**
     * Bootstrap the application services.
     */
    public function boot(Repository $configRepository)
    {
        $this->configRepository = $configRepository;

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/querylog.php' => config_path('querylog.php'),
            ], 'config');
        }

        $this->get_log_channels();

        if (config('querylog.query_log_enable') == true) {
            /// Log all queries executed, performed by Application
            DB::connection()->enableQueryLog();
            DB::listen(function ($query) {
                try {
                    if (config('querylog.log_chhanels') == null) {
                        $this->defaultQueryLogger()->info($this->BindQueryLog($query->sql, $query->bindings));
                    } else {
                        Log::stack($this->get_log_channels())->info($this->BindQueryLog($query->sql, $query->bindings));
                    }
                } catch (Exception $t) {
                    throw new QueryLogException($t->getMessage());
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
        $app_channels = collect($this->configRepository->get('logging')['channels'])->pluck('driver');
        $user_defined_channels = collect(array_filter(explode(',', config('querylog.log_chhanels'))));
        return $app_channels->intersect($user_defined_channels)->toArray();
        # (string)"one,two" => ['one', 'two'];
    }

    /**
     * By default log queries in db-query log file
     * @return Logger
     */
    public function defaultQueryLogger()
    {
        return new Logger('Query', [
            new StreamHandler(
                $this->app->storagePath() . '/logs/db-query.log'
                , Logger::DEBUG
            )
        ]);
    }

    /**
     * Bind-Query parameters in Query string
     *
     * @param $sql
     * @param $binds
     *
     * @return string
     */
    public function BindQueryLog($sql, $binds)
    {
        if (empty($binds)) {
            return $sql;
        }
        $sql = str_replace(['%', '?'], ['%%', '%s'], $sql);
        return vsprintf($sql, $binds);

        /*$result = "";
        $sql_chunks = explode('?', $sql);
        foreach ($sql_chunks as $key => $sql_chunk) {
            if (isset($binds[$key])) {
                $result .= $sql_chunk . '"' . $binds[$key] . '"';
            }
        }
        $result .= $sql_chunks[count($sql_chunks) - 1];
        return $result;*/
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/querylog.php', 'querylog');
    }

}