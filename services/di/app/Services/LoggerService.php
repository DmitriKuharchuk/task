<?php
namespace App\Services;
class LoggerService { public function log(string $m): string { return '[LOGGER] '.$m; } }
