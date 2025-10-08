<?php
namespace App\Services;
class TestService {
  public function __construct(private LoggerService $logger) {}
  public function run(): string { return $this->logger->log('DI container working!'); }
}
