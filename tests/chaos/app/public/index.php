<?php

// final class Hoge
// {
//     private $redis;

//     public function __construct() {
//         session_set_save_handler($this);
//         $this->redis = new Redis();
//     }
// }

// $hoge = new Hoge();

error_reporting(E_ALL);

// By default we return error, only as the last thing we set 200 instead
http_response_code(500);

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

http_response_code(200);
echo "$output\n";
