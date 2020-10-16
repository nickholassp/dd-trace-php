<?php

error_reporting(E_ALL);

http_response_code(500);

ob_start();

// \set_exception_handler(function () {
//     // error_log('Exception: ' . var_export($ex->getMessage(), 1));
//     http_response_code(500);
// });

// trigger_error('aaa', E_USER_ERROR);

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
$output = $controller->action() . "\n";

echo "$output\n";

http_response_code(200);
ob_flush();
