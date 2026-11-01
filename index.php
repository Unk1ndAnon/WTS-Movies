<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Keep the homepage on the same cached TMDB client used by catalogue pages.
$homepageLanguage = 'en-US';
if (isset($language) && is_string($language)) {
    $languageParams = [];
    parse_str(ltrim($language, '&?'), $languageParams);
    if (
        !empty($languageParams['language']) &&
        is_string($languageParams['language']) &&
        preg_match('/^[a-z]{2}(?:-[A-Z]{2})?$/', $languageParams['language'])
    ) {
        $homepageLanguage = $languageParams['language'];
    }
}

$trndngmvs = tmdb_request('/trending/movie/week', ['language' => $homepageLanguage], 1800) ?? [];
$trndngtv = tmdb_request('/trending/tv/week', ['language' => $homepageLanguage], 1800) ?? [];
$movieResults = is_array($trndngmvs['results'] ?? null) ? $trndngmvs['results'] : [];
$tvResults = is_array($trndngtv['results'] ?? null) ? $trndngtv['results'] : [];

// Prefer a title with a landscape backdrop for the large cinematic feature.
$heroMovie = null;
foreach ($movieResults as $candidate) {
    if (is_array($candidate) && !empty($candidate['backdrop_path'])) {
        $heroMovie = $candidate;
        break;
    }
}
if ($heroMovie === null && !empty($movieResults[0]) && is_array($movieResults[0])) {
    $heroMovie = $movieResults[0];
}

$heroId = (int)($heroMovie['id'] ?? 0);
$heroTitle = (string)($heroMovie['title'] ?? 'Discover something great');
$heroSlug = slugify($heroTitle);
$heroYear = !empty($heroMovie['release_date']) ? substr((string)$heroMovie['release_date'], 0, 4) : '';
$heroRating = Ratingtwo($heroMovie['vote_average'] ?? 0);
$heroOverview = trim((string)($heroMovie['overview'] ?? ''));
$heroBackdrop = tmdb_image($heroMovie['backdrop_path'] ?? $heroMovie['poster_path'] ?? '', 'w1280');

$metatitle = $heroId > 0
    ? $heroTitle . ' and Trending Movies & TV Shows | TStream WATCH'
    : 'Watch Movies and TV Shows Online | TStream WATCH';
$metadesc = $heroOverview !== ''
    ? $heroOverview
    : 'Discover trending movies, popular films and TV series. Explore new releases, ratings, cast details and episodes on TStream WATCH.';
$metaimg = $heroBackdrop;
$canonical = '';
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<main id="container" class="ott-home">
  <section class="ott-hero" aria-label="Featured movie">
    <img class="ott-hero-backdrop" src="<?= e($heroBackdrop) ?>" alt="" fetchpriority="high" decoding="async">
    <div class="ott-hero-shade" aria-hidden="true"></div>

    <div class="ott-hero-copy">
      <span class="ott-hero-kicker">TSTREAM WATCH PRESENTS</span>
      <div class="ott-hero-meta">
        <span>FEATURED MOVIE</span>
        <?php if ($heroYear !== ''): ?><span><?= e($heroYear) ?></span><?php endif; ?>
        <?php if ($heroRating !== 'N/A'): ?><span class="ott-hero-rating">★ <?= e($heroRating) ?>/10</span><?php endif; ?>
      </div>
      <h1><?= e($heroTitle) ?></h1>
      <p><?= e($heroOverview !== '' ? $heroOverview : 'Explore the latest movies and series, hand-picked from what is trending right now.') ?></p>
      <?php if ($heroId > 0): ?>
      <div class="ott-hero-actions">
        <a class="ott-watch-button" href="/movies/<?= $heroId ?>/<?= e($heroSlug) ?>#player">
          <i class="fa fa-play" aria-hidden="true"></i> Watch Now
        </a>
        <a class="ott-info-button" href="/movies/<?= $heroId ?>/<?= e($heroSlug) ?>">More Info</a>
      </div>
      <?php else: ?>
      <div class="ott-hero-actions">
        <a class="ott-watch-button" href="/movies"><i class="fa fa-film" aria-hidden="true"></i> Explore Movies</a>
      </div>
      <?php endif; ?>
    </div>

    <div class="ott-hero-rail">
      <div class="ott-section-heading">
        <div>
          <span class="ott-section-eyebrow">WHAT TO WATCH</span>
          <h2>Trending Movies</h2>
        </div>
        <a href="/trending-movies">View all <span aria-hidden="true">→</span></a>
      </div>
      <div class="ott-movie-track" aria-label="Trending movies">
        <?php foreach (array_slice($movieResults, 0, 8) as $movie): ?>
          <?php
            $movieId = (int)($movie['id'] ?? 0);
            $movieTitle = (string)($movie['title'] ?? 'Movie');
            if ($movieId < 1) continue;
          ?>
          <a class="ott-movie-card" href="/movies/<?= $movieId ?>/<?= e(slugify($movieTitle)) ?>">
            <img src="<?= e(tmdb_image($movie['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($movieTitle) ?>" loading="lazy" decoding="async">
            <span class="ott-card-shade" aria-hidden="true"></span>
            <span class="ott-card-rating">★ <?= e(Ratingtwo($movie['vote_average'] ?? 0)) ?></span>
            <span class="ott-card-title"><?= e($movieTitle) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <div class="ott-home-sections">
    <section class="ott-home-row" aria-labelledby="ott-tv-heading">
      <div class="ott-section-heading">
        <div>
          <span class="ott-section-eyebrow">SERIES TO DISCOVER</span>
          <h2 id="ott-tv-heading">Trending TV Shows</h2>
        </div>
        <a href="/trending-tv">View all <span aria-hidden="true">→</span></a>
      </div>
      <div class="ott-movie-track ott-tv-track">
        <?php foreach (array_slice($tvResults, 0, 8) as $show): ?>
          <?php
            $showId = (int)($show['id'] ?? 0);
            $showTitle = (string)($show['name'] ?? 'TV Show');
            if ($showId < 1) continue;
          ?>
          <a class="ott-movie-card" href="/tv/<?= $showId ?>/<?= e(slugify($showTitle)) ?>">
            <img src="<?= e(tmdb_image($show['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($showTitle) ?>" loading="lazy" decoding="async">
            <span class="ott-card-shade" aria-hidden="true"></span>
            <span class="ott-card-rating">★ <?= e(Ratingtwo($show['vote_average'] ?? 0)) ?></span>
            <span class="ott-card-title"><?= e($showTitle) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
