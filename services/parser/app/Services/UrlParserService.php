<?php

namespace App\Services;


class UrlParserService
{

    public function parse(string $url): array
    {
        $normalizedUrl = $this->normalizeUrl($url);

        $parsed = $this->parseUrlComponents($normalizedUrl);
        $parsed = $this->expandDomainStructure($parsed);
        $parsed['origin'] = $this->buildOrigin($parsed);
        $parsed['path'] = $parsed['path'] ?? '/';

        return $parsed;
    }

    private function normalizeUrl(string $url): string
    {
        if (!preg_match('#^[a-z]+://#i', $url) || !function_exists('idn_to_ascii')) {
            return $url;
        }

        return preg_replace_callback(
            '#://([^/]+)#',
            fn($match) => '://'.idn_to_ascii($match[1], IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46),
            $url
        );
    }

    private function parseUrlComponents(string $url): array
    {
        $parsed = parse_url($url);

        if ($parsed === false) {
            return [];
        }

        $result = [];

        $result['scheme'] = $parsed['scheme'] ?? null;
        $result['host'] = $parsed['host'] ?? null;
        $result['port'] = $parsed['port'] ?? null;
        $result['path'] = $parsed['path'] ?? '';

        if (isset($parsed['query'])) {
            $result['query_params'] = $this->queryStringToArray($parsed['query']);
        }

        return $result;
    }

    private function queryStringToArray(string $query): array
    {
        $params = [];
        parse_str($query, $params);
        return $params;
    }


    private function expandDomainStructure(array $parsed): array
    {
        if (!isset($parsed['host'])) {
            return $parsed;
        }

        $parts = array_reverse(explode('.', $parsed['host']));
        $tld = array_pop($parts);
        $sld = array_pop($parts);
        $subdomain = !empty($parts) ? implode('.', $parts) : null;

        if (function_exists('idn_to_utf8')) {
            $parsed['host_unicode'] = idn_to_utf8($parsed['host'], IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        }

        $parsed['tld'] = $tld;
        $parsed['sld'] = $sld;
        $parsed['domain'] = $sld . '.' . $tld;
        $parsed['subdomain'] = $subdomain;

        return $parsed;
    }

    private function buildOrigin(array $parsed): ?string
    {
        if (!isset($parsed['scheme'], $parsed['host'])) {
            return null;
        }

        $port = $parsed['port'] ?? '';
        $portPart = $port ? ':'.$port : '';

        return $parsed['scheme'] . '://' . $parsed['host'] . $portPart;
    }
}
