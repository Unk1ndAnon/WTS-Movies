<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
if (!isset($metatitle) || trim((string)$metatitle) === '') $metatitle = SITE_NAME . ' - Watch Movies & TV Shows';
if (!isset($metadesc) || trim((string)$metadesc) === '') $metadesc = 'Discover movies and TV series on TStream WATCH. Explore trending titles, new releases and popular shows.';
if (!isset($metaimg) || trim((string)$metaimg) === '') $metaimg = site_url('img/home.jpg');
$metaimg = (string)$metaimg;
$robots = $robots ?? 'index,follow';
$ogtype = $ogtype ?? 'website';
if (!isset($canonical) || trim((string)$canonical) === '') {
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $canonical = trim($requestPath, '/');
}
$canonicalUrl = site_url((string)$canonical);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#090a0d">
<meta name="robots" content="<?= e($robots) ?>">
<title><?= e($metatitle) ?></title>
<meta name="description" content="<?= e(function_exists('mb_substr') ? mb_substr((string)$metadesc, 0, 160, 'UTF-8') : substr((string)$metadesc, 0, 160)) ?>">
<link rel="canonical" href="<?= e($canonicalUrl) ?>">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:type" content="<?= e($ogtype) ?>">
<meta property="og:title" content="<?= e($metatitle) ?>">
<meta property="og:description" content="<?= e($metadesc) ?>">
<meta property="og:url" content="<?= e($canonicalUrl) ?>">
<meta property="og:image" content="<?= e($metaimg) ?>">
<meta property="og:image:alt" content="<?= e($metatitle) ?>">
<meta property="og:locale" content="en_US">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($metatitle) ?>">
<meta name="twitter:description" content="<?= e($metadesc) ?>">
<meta name="twitter:image" content="<?= e($metaimg) ?>">
<link rel="icon" type="image/svg+xml" href="/img/icon.svg">
<link rel="manifest" href="/favicon/site.webmanifest">
<link rel="preconnect" href="https://image.tmdb.org" crossorigin>
<link rel="preconnect" href="https://api.themoviedb.org">
<link rel="stylesheet" href="/CSS/style.css?v=2.0">
<?php if (!empty($metaschema)) echo $metaschema; ?>
</head>
<body>
<div id="data-container">
<header class="responsive tstream-header">
  <button id="menubtn" class="nav" type="button" aria-label="Open navigation" aria-expanded="false"><i class="fa fa-bars" aria-hidden="true"></i></button>
  <div class="logo"><a href="/" aria-label="TStream WATCH home"><img src="/img/logo.svg" alt="TStream WATCH" width="190" height="48"></a></div>
  <button id="searchbtn" class="search" type="button" aria-label="Open search" aria-expanded="false"><i class="fa fa-search" aria-hidden="true"></i></button>
</header>
<div class="search_responsive">
  <form method="get" id="form-search-resp" class="form-resp-ab" action="/search">
    <label class="sr-only" for="keyword">Search movies and TV shows</label>
    <input type="search" class="footer_search_input" placeholder="Search movies and TV shows..." name="keyword" id="keyword" autocomplete="off">
    <button type="submit" class="search-submit">Search</button>
  </form>
  <div class="live-search" id="header_search_autocomplete" aria-live="polite"></div>
</div>
<div id="arch-menu" class="menuresp">
  <nav class="menu" aria-label="Main navigation">
    <ul class="resp">
      <li><a href="/"><i class="fas fa-home" aria-hidden="true"></i><div class="mvs">Home</div></a></li>
      <li><a href="/movies"><i class="fas fa-film" aria-hidden="true"></i><div class="mvs">Movies</div></a></li>
      <li><a href="/trending-movies"><i class="fas fa-chart-line" aria-hidden="true"></i><div class="mvs">Trending Movies</div></a></li>
      <li><a href="/top-rated-movies"><i class="fas fa-star" aria-hidden="true"></i><div class="mvs">Top Rated Movies</div></a></li>
      <li><a href="/tv"><i class="fas fa-tv" aria-hidden="true"></i><div class="mvs">TV Shows</div></a></li>
      <li><a href="/trending-tv"><i class="fas fa-chart-line" aria-hidden="true"></i><div class="mvs">Trending TV Shows</div></a></li>
      <li><a href="/top-rated-tv"><i class="fas fa-star" aria-hidden="true"></i><div class="mvs">Top Rated TV Shows</div></a></li>
    </ul>
  </nav>
</div>
