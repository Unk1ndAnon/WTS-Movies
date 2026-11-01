<?php
declare(strict_types=1);

/** Escape all dynamic content before inserting it into HTML attributes or text. */
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function slugify($text): string {
    $text = trim((string)$text);
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) $text = $converted;
    }
    $text = strtolower((string)preg_replace('~[^a-zA-Z0-9]+~', '-', $text));
    $text = trim((string)preg_replace('~-+~', '-', $text), '-');
    return $text !== '' ? $text : 'title';
}

function slugged($value): string {
    $id = preg_replace('/[^0-9]/', '', (string)$value);
    return $id !== '' ? $id : '0';
}

function RemoveSpecialChar($value): string {
    return trim((string)preg_replace('/[\\x00-\\x1F\\x7F]/u', ' ', (string)$value));
}

function Ratingtwo($value): string {
    $number = (float)$value;
    return $number > 0 ? number_format($number, 1, '.', '') : 'N/A';
}

function tmdb_request(string $endpoint, array $params = [], int $ttl = 3600): ?array {
    global $TMDB_API_KEY, $TMDB_CACHE_DIR;
    if ($TMDB_API_KEY === '') return null;

    $endpoint = '/' . ltrim($endpoint, '/');
    if (strpos($endpoint, '..') !== false) return null;
    unset($params['api_key']);
    $params['api_key'] = $TMDB_API_KEY;
    $params['language'] = $params['language'] ?? 'en-US';

    $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    $url = 'https://api.themoviedb.org/3' . $endpoint . '?' . $query;
    $cacheDir = (string)$TMDB_CACHE_DIR;
    if (!is_dir($cacheDir)) @mkdir($cacheDir, 0750, true);
    $cacheFile = $cacheDir . '/' . hash('sha256', $endpoint . '?' . http_build_query(array_diff_key($params, ['api_key' => true]))) . '.json';

    if (is_file($cacheFile) && (time() - (int)@filemtime($cacheFile) < $ttl)) {
        $cached = @file_get_contents($cacheFile);
        $decoded = is_string($cached) ? json_decode($cached, true) : null;
        if (is_array($decoded)) return $decoded;
    }

    $body = false;
    $status = 0;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'TStreamWATCH/1.0 (+https://tstream.watch)',
            CURLOPT_HTTPHEADER => ['Accept: application/json']
        ]);
        $body = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => ['timeout' => 10, 'ignore_errors' => true, 'header' => "Accept: application/json\r\nUser-Agent: TStreamWATCH/1.0\r\n"],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'allow_self_signed' => false]
        ]);
        $body = @file_get_contents($url, false, $context);
        if (isset($http_response_header) && is_array($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (preg_match('/^HTTP\\/\\S+\\s+(\\d{3})/', $header, $m)) $status = (int)$m[1];
            }
        }
    }
    if (!is_string($body) || $status < 200 || $status >= 300) return null;
    $data = json_decode($body, true);
    if (!is_array($data)) return null;

    if (is_dir($cacheDir) && is_writable($cacheDir)) {
        $tmp = $cacheFile . '.' . bin2hex(random_bytes(4)) . '.tmp';
        if (@file_put_contents($tmp, $body, LOCK_EX) !== false) @rename($tmp, $cacheFile);
    }
    return $data;
}

/**
 * Compatibility wrapper for legacy pages which previously fetched TMDB URLs
 * directly. Only api.themoviedb.org is accepted; TLS verification and cache
 * are enforced by tmdb_request().
 */
function tmdb_fetch_url($url) {
    $parts = parse_url((string)$url);
    if (!$parts || ($parts['host'] ?? '') !== 'api.themoviedb.org') return false;
    parse_str($parts['query'] ?? '', $params);
    unset($params['api_key']);
    $data = tmdb_request($parts['path'] ?? '/', $params);
    return $data === null ? false : json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function tmdb_image($path, string $size = 'w500'): string {
    return $path ? 'https://image.tmdb.org/t/p/' . rawurlencode($size) . '/' . ltrim((string)$path, '/') : '/img/noposter.png';
}

function site_url(string $path = ''): string {
    return rtrim(SITE_URL, '/') . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}

function json_ld(array $data): string {
    return '<script type="application/ld+json">' .
        json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) .
        '</script>';
}
