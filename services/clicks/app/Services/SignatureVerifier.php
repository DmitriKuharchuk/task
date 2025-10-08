<?php
namespace App\Services;

final class SignatureVerifier
{
    public function __construct(private array $sources) {}

    public function verify(string $source, string $rawJson, ?string $signature): bool
    {
        if (!isset($this->sources[$source]['secret'])) {
            return false;
        }
        $secret = $this->sources[$source]['secret'];
        $calc = hash_hmac('sha256', $rawJson, $secret);
        return hash_equals($calc, (string)$signature);
    }
}
