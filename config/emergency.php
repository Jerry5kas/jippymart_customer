<?php
// EMERGENCY CONFIGURATION
// This file contains critical settings to prevent 503/508 errors

return [
    "memory_limit" => "64M",
    "max_execution_time" => 15,
    "firebase_query_limit" => 20,
    "firebase_timeout" => 10,
    "disable_async" => true,
    "disable_background_jobs" => true,
    "force_garbage_collection" => true,
    "optimize_database" => true,
    "cache_driver" => "file",
    "session_driver" => "file",
    "queue_connection" => "sync"
];
