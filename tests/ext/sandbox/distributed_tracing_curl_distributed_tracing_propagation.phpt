--TEST--
Distributed tracing headers propagate
--SKIPIF--
<?php if (!extension_loaded('curl')) die('skip: curl extension required'); ?>
<?php if (!getenv('HTTPBIN_HOSTNAME')) die('skip: HTTPBIN_HOSTNAME env var required'); ?>
--ENV--
DD_TRACE_DEBUG=0
DD_TRACE_TRACED_INTERNAL_FUNCTIONS=curl_exec
--INI--
ddtrace.request_init_hook=../../bridge/dd_wrap_autoloader.php
--FILE--
<?php

use DDTrace\GlobalTracer;
$tracer = GlobalTracer::get();
$scope = $tracer->getRootScope();
$span = $scope->getSpan();
$context = $span->getContext();
$context->origin = 'phpt-test';
$context->propagatedPrioritySampling = 1;
$context->parentId = '789';

$port = getenv('HTTPBIN_PORT') ?: '80';
$url = 'http://' . getenv('HTTPBIN_HOSTNAME') . ':' . $port .'/headers';
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
]);

$responses = [];
$responses[] = curl_exec($ch);
curl_close($ch);

$scope->close();
$tracer->flush();

include 'distributed_tracing.inc';
foreach ($responses as $key => $response) {
    echo 'Response #' . $key . PHP_EOL;
    $headers = dt_decode_headers_from_httpbin($response);
    dt_dump_headers_from_httpbin($headers, [
        'x-datadog-trace-id',
        'x-datadog-parent-id',
        'x-datadog-origin',
    ]);
    echo PHP_EOL;
}

echo 'Done.' . PHP_EOL;
?>
--EXPECTF--
Response #0
x-datadog-origin: phpt-test
x-datadog-parent-id: %d
x-datadog-trace-id: %d

Done.
