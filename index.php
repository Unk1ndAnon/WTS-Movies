<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// All homepage catalogues use the shared TMDB client and its file cache.
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

$trendingMovieResponse = tmdb_request('/trending/movie/week', ['language' => $homepageLanguage], 1800) ?? [];
$popularMovieResponse = tmdb_request('/movie/popular', ['language' => $homepageLanguage], 1800) ?? [];
$trendingTvResponse = tmdb_request('/trending/tv/week', ['language' => $homepageLanguage], 1800) ?? [];

$trendingMovies = is_array($trendingMovieResponse['results'] ?? null) ? $trendingMovieResponse['results'] : [];
$popularMovies = is_array($popularMovieResponse['results'] ?? null) ? $popularMovieResponse['results'] : [];
$trendingShows = is_array($trendingTvResponse['results'] ?? null) ? $trendingTvResponse['results'] : [];

// Use a real TMDB trending title and its backdrop for the cinematic feature.
$heroMovie = null;
foreach ($trendingMovies as $candidate) {
    if (is_array($candidate) && !empty($candidate['backdrop_path'])) {
        $heroMovie = $candidate;
        break;
    }
}
if ($heroMovie === null && !empty($popularMovies[0]) && is_array($popularMovies[0])) {
    $heroMovie = $popularMovies[0];
}
if ($heroMovie === null && !empty($trendingMovies[0]) && is_array($trendingMovies[0])) {
    $heroMovie = $trendingMovies[0];
}

$heroId = (int)($heroMovie['id'] ?? 0);
$heroTitle = (string)($heroMovie['title'] ?? 'Discover something great');
$heroSlug = slugify($heroTitle);
$heroYear = !empty($heroMovie['release_date']) ? substr((string)$heroMovie['release_date'], 0, 4) : '';
$heroRating = Ratingtwo($heroMovie['vote_average'] ?? 0);
$heroOverview = trim((string)($heroMovie['overview'] ?? ''));
$heroBackdrop = tmdb_image($heroMovie['backdrop_path'] ?? $heroMovie['poster_path'] ?? '', 'w1280');

// Runtime is supplemental metadata; the homepage still renders if the detail request fails.
$heroDetails = $heroId > 0
    ? (tmdb_request('/movie/' . $heroId, ['language' => $homepageLanguage], 3600) ?? [])
    : [];
$heroRuntime = (int)($heroDetails['runtime'] ?? 0);
$heroRuntimeLabel = '';
if ($heroRuntime > 0) {
    $runtimeHours = intdiv($heroRuntime, 60);
    $runtimeMinutes = $heroRuntime % 60;
    $heroRuntimeLabel = $runtimeHours > 0
        ? $runtimeHours . 'h' . ($runtimeMinutes > 0 ? ' ' . $runtimeMinutes . 'm' : '')
        : $runtimeMinutes . 'm';
}

$collectionCards = !empty($popularMovies) ? $popularMovies : $trendingMovies;

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
      <span class="ott-hero-kicker">Featured movie</span>
      <div class="ott-hero-meta">
        <?php if ($heroYear !== ''): ?><span><?= e($heroYear) ?></span><?php endif; ?>
        <?php if ($heroRuntimeLabel !== ''): ?><span><?= e($heroRuntimeLabel) ?></span><?php endif; ?>
        <?php if ($heroRating !== 'N/A'): ?><span class="ott-hero-rating"><strong>TMDB</strong> ★ <?= e($heroRating) ?>/10</span><?php endif; ?>
      </div>
      <h1><?= e($heroTitle) ?></h1>
      <p><?= e($heroOverview !== '' ? $heroOverview : 'Discover the latest movies and series, selected from what viewers are watching right now.') ?></p>
      <div class="ott-hero-actions">
        <?php if ($heroId > 0): ?>
          <a class="ott-watch-button" href="/movies/<?= $heroId ?>/<?= e($heroSlug) ?>#player">
            <i class="fa fa-play" aria-hidden="true"></i> Watch Now
          </a>
          <a class="ott-info-button" href="/movies/<?= $heroId ?>/<?= e($heroSlug) ?>">More Info</a>
        <?php else: ?>
          <a class="ott-watch-button" href="/movies"><i class="fa fa-film" aria-hidden="true"></i> Explore Movies</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="ott-collection-shelf">
      <div class="ott-collection-heading">
        <div>
          <span class="ott-section-eyebrow">EXPLORE THE LIBRARY</span>
          <h2>Popular Collections</h2>
        </div>
        <a href="/movies">Explore all <span aria-hidden="true">→</span></a>
      </div>

      <div class="ott-collection-grid" aria-label="Popular movies">
        <?php foreach (array_slice($collectionCards, 0, 6) as $movie): ?>
          <?php
            $movieId = (int)($movie['id'] ?? 0);
            $movieTitle = (string)($movie['title'] ?? 'Movie');
            if ($movieId < 1) continue;
            $movieYear = !empty($movie['release_date']) ? substr((string)$movie['release_date'], 0, 4) : '';
            $movieRating = Ratingtwo($movie['vote_average'] ?? 0);
          ?>
          <a class="ott-collection-card" href="/movies/<?= $movieId ?>/<?= e(slugify($movieTitle)) ?>">
            <img src="<?= e(tmdb_image($movie['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($movieTitle) ?>" loading="lazy" decoding="async">
            <span class="ott-collection-overlay" aria-hidden="true"></span>
            <span class="ott-collection-info">
              <strong><?= e($movieTitle) ?></strong>
              <small><?= e($movieYear !== '' ? $movieYear : 'Movie') ?><?php if ($movieRating !== 'N/A'): ?> <span>•</span> ★ <?= e($movieRating) ?><?php endif; ?></small>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <div class="ott-home-sections">
    <section class="ott-home-row" aria-labelledby="ott-trending-movies-heading">
      <div class="ott-content-heading">
        <div>
          <span class="ott-section-eyebrow">WHAT EVERYONE IS WATCHING</span>
          <h2 id="ott-trending-movies-heading">Trending Movies</h2>
        </div>
        <a href="/trending-movies">View all <span aria-hidden="true">→</span></a>
      </div>
      <div class="ott-poster-track" aria-label="Trending movies">
        <?php foreach (array_slice($trendingMovies, 0, 12) as $movie): ?>
          <?php
            $movieId = (int)($movie['id'] ?? 0);
            $movieTitle = (string)($movie['title'] ?? 'Movie');
            if ($movieId < 1) continue;
          ?>
          <a class="ott-poster-card" href="/movies/<?= $movieId ?>/<?= e(slugify($movieTitle)) ?>">
            <span class="ott-poster-image">
              <img src="<?= e(tmdb_image($movie['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($movieTitle) ?>" loading="lazy" decoding="async">
              <span class="ott-poster-rating">★ <?= e(Ratingtwo($movie['vote_average'] ?? 0)) ?></span>
            </span>
            <strong><?= e($movieTitle) ?></strong>
            <small><?= !empty($movie['release_date']) ? e(substr((string)$movie['release_date'], 0, 4)) : 'Movie' ?></small>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="ott-home-row" aria-labelledby="ott-trending-tv-heading">
      <div class="ott-content-heading">
        <div>
          <span class="ott-section-eyebrow">SERIES WORTH WATCHING</span>
          <h2 id="ott-trending-tv-heading">Trending TV Shows</h2>
        </div>
        <a href="/trending-tv">View all <span aria-hidden="true">→</span></a>
      </div>
      <div class="ott-poster-track" aria-label="Trending TV shows">
        <?php foreach (array_slice($trendingShows, 0, 12) as $show): ?>
          <?php
            $showId = (int)($show['id'] ?? 0);
            $showTitle = (string)($show['name'] ?? 'TV Show');
            if ($showId < 1) continue;
          ?>
          <a class="ott-poster-card" href="/tv/<?= $showId ?>/<?= e(slugify($showTitle)) ?>">
            <span class="ott-poster-image">
              <img src="<?= e(tmdb_image($show['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($showTitle) ?>" loading="lazy" decoding="async">
              <span class="ott-poster-rating">★ <?= e(Ratingtwo($show['vote_average'] ?? 0)) ?></span>
            </span>
            <strong><?= e($showTitle) ?></strong>
            <small><?= !empty($show['first_air_date']) ? e(substr((string)$show['first_air_date'], 0, 4)) : 'TV Series' ?></small>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
