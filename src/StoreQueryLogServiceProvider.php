<?php

namespace Dipenparmar12\QueryLog;

use Dipenparmar12\QueryLog\Exceptions\QueryLogException;
use Throwable;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class StoreQueryLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(ConfigRepository $configRepository): void
    {
        $this->publishConfig();

        if (config('querylog.query_log_enable') === true) {
            /// Log all queries executed, performed by Application
            DB::connection()->enableQueryLog();

            DB::listen(function ($query) use ($configRepository) {
                try {
                    $queryString = $this->bindQueryLog($query->sql, $query->bindings);

                    if (config('querylog.log_chhanels') === null) {
                        $this->getDefaultQueryLogger()->info($queryString);
                    } else {
                        Log::stack($this->getLogChannels($configRepository))->info($queryString);
                    }
                } catch (Throwable $e) {
                    throw new QueryLogException($e->getMessage());
                }
            });
        }
    }

    private function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/querylog.php' => config_path('querylog.php'),
            ], 'config');
        }
    }

    /**
     * @param ConfigRepository $configRepository
     *
     * @return array
     */
    protected function getLogChannels(ConfigRepository $configRepository): array
    {
        $appChannels = collect(
            $configRepository->get('logging')['channels']
        )->keys();

        $customChannels = collect(
            explode(',', config('querylog.log_chhanels'))
        )->filter();

        return $appChannels->intersect($customChannels)->toArray();
    }

    /**
     * By default log queries in db-query log file
     *
     * @return Logger
     */
    public function getDefaultQueryLogger(): Logger
    {
        return new Logger('db-query', [
            new StreamHandler(
                $this->app->storagePath() . '/logs/db-query.log',
                Logger::DEBUG
            )
        ]);
    }

    /**
     * Bind-Query parameters in Query string
     *
     * @param string $sql
     * @param array  $bindings
     *
     * @return string
     */
    public function bindQueryLog(string $sql, array $bindings): string
    {
        if (empty($bindings)) {
            return $sql;
        }

        $sql = str_replace(['%', '?'], ['%%', '%s'], $sql);

        return vsprintf($sql, $bindings);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/querylog.php', 'querylog');
    }

}
