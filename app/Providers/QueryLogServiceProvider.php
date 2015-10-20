<?php namespace App\Providers;

use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Current query count
     *
     * @var integer
     */
    private $countQueries = 0;

    /**
     * Environment
     *
     * @var string
     */
    private $environment;

    /**
     * Request start time
     *
     * @var integer
     */
    private $startTime;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (config('database.log')) {
            $this->environment = $this->app->environment();
            $this->startTime = microtime();

            Event::listen('illuminate.query', function ($query, $bindings) {
                $bindings = $this->formatBindingsForSqlInjection($bindings);
                $query    = $this->insertBindingsIntoQuery($query, $bindings);
                $this->countQueries++;

                Log::info(sprintf('--- Query %s --- %s', $this->countQueries, $query));
            });

            register_shutdown_function(function () {

                Log::info(sprintf(
                    '*** "%s" Request Completed (%s microseconds) ***',
                    $this->environment,
                    (microtime() - $this->startTime)
                ));
            });
        }
    }

    /**
     * @param $bindings
     *
     * @return mixed
     */
    private function formatBindingsForSqlInjection($bindings)
    {
        foreach ($bindings as $i => $binding) {
            if ($binding instanceof DateTime) {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else {
                if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }
        }

        return $bindings;
    }

    /**
     * @param $query
     * @param $bindings
     *
     * @return string
     */
    private function insertBindingsIntoQuery($query, $bindings)
    {
        if (empty($bindings)) {
            return $query;
        }

        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);

        return vsprintf($query, $bindings);
    }
}
