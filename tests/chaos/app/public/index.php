<?php

error_reporting(E_ALL);

\set_exception_handler(function ($ex) {
    header('HTTP/1.1 500 Internal Server Error');
});

require __DIR__ . '/../vendor/autoload.php';

// Do not use function_exists('DDTrace\...') because if DD_TRACE_ENABLED is not false and the function does not exist
// then we MUST generate an error
if (getenv('DD_TRACE_ENABLED') !== 'false') {
    // Tracing manual functions
    $callback = function (\DDTrace\SpanData $span) {
        $span->service = \ddtrace_config_app_name();
    };
    \dd_trace_method('App\Service', 'doSomethingTraced', $callback);
    \dd_trace_method('App\Service', 'doSomethingTraced1', $callback);
    \dd_trace_method('App\Service', 'doSomethingTraced2', $callback);
}


$controller = new \App\Controller();
echo $controller->action() . "\n";
