Investigation:
    - Default: would have found critical regressions in 0.48.0, 0.48.1, 0.47.0 (on 5.4 commenting `dd_trace_method` in index.php which was not available yet)
    - DD_TRACE_ENABLED: true|false --> would have found bug 0.48.2
    - Would NOT have found: regression introduced in 0.45.0 (psr4 compatility) unless we add manual instrumentation via composer.
