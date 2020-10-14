<?php

require __DIR__ . '/../vendor/autoload.php';

// Tracing manual functions
$callback = function (\DDTrace\SpanData $span) {
    $span->service = \ddtrace_config_app_name();
};
\DDTrace\trace_method('App\Service', 'doSomethingTraced', $callback);
\DDTrace\trace_method('App\Service', 'doSomethingTraced1', $callback);
\DDTrace\trace_method('App\Service', 'doSomethingTraced2', $callback);

$controller = new \App\Controller();
$controller->action();
