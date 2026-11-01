<?php
declare(strict_types=1);

/**
 * TStream WATCH configuration.
 * Put private keys in environment variables or includes/config.local.php.
 */
$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    require $localConfig;
}

$SiteUrl = rtrim((string)($SITE_URL ?? getenv('SITE_URL') ?: 'https://tstream.watch'), '/');
if (!filter_var($SiteUrl, FILTER_VALIDATE_URL) || !in_array(parse_url($SiteUrl, PHP_URL_SCHEME), ['http', 'https'], true)) {
    $SiteUrl = 'https://tstream.watch';
}
if (!defined('SITE_URL')) {
    define('SITE_URL', $SiteUrl);
}
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'TStream WATCH');
}

$TMDB_API_KEY = trim((string)($TMDB_API_KEY ?? getenv('TMDB_API_KEY') ?: ''));
$APIbaseURL = 'https://api.themoviedb.org/3/';
$api_key = '?api_key=' . rawurlencode($TMDB_API_KEY);
$Domainname = (string)parse_url(SITE_URL, PHP_URL_HOST);
$SiteTitle = SITE_NAME;
$FooterTitle = SITE_NAME;
$aeserver = 1;

$directlink = '';
$movie = 'movie/';
$tv = 'tv/';
$language = '&language=en-US';
$popular = 'popular';
$latest = 'now_playing';
$tvlatest = 'on_the_air';
$searchmovie = 'search/movie';
$searchtv = 'search/tv';
$trendingmovieweek = 'trending/movie/week';
$trendingtvweek = 'trending/tv/week';
$trendingmovieday = 'trending/movie/day';
$trendingtvday = 'trending/tv/day';
$topratedmovie = 'movie/top_rated';
$topratedtv = 'tv/top_rated';
$videos = '/videos';

$TMDB_CACHE_DIR = dirname(__DIR__) . '/storage/cache';
$TMDB_CACHE_TTL = 3600;
