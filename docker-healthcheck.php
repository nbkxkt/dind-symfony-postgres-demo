<?php

declare(strict_types=1);

$body = @file_get_contents('http://127.0.0.1:8000/healthz');
if ($body === false) {
    fwrite(STDERR, "application endpoint unavailable\n");
    exit(1);
}

$data = json_decode($body, true);
if (!is_array($data) || ($data['status'] ?? null) !== 'ok' || ($data['sample_rows'] ?? 0) < 1) {
    fwrite(STDERR, "database-backed health check failed\n");
    exit(1);
}

echo "internal_app_database=ok\n";
