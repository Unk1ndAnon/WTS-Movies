<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/stream-embed.php';

$id = slugged($_GET['id'] ?? '');
if ($id === '0') { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$currentmovie = tmdb_request('/movie/' . $id, ['append_to_response' => 'videos,credits,recommendations'], 21600);
if (!$currentmovie || empty($currentmovie['id'])) { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$latestmovies = tmdb_request('/trending/movie/day', [], 1800) ?? ['results' => []];
$title = (string)($currentmovie['title'] ?? 'Movie');
$year = !empty($currentmovie['release_date']) ? substr((string)$currentmovie['release_date'], 0, 4) : '';
$slug = slugify($title);
$path = '/movies/' . $id . '/' . $slug;
$canonical = $path;
$ogtype = 'video.movie';
$metatitle = $title . ($year ? " ({$year})" : '') . ' - Watch on ' . SITE_NAME;
$overview = trim((string)($currentmovie['overview'] ?? ''));
$metadesc = $overview !== '' ? 'Watch ' . $title . ($year ? " ({$year})" : '') . ' online. ' . $overview : 'Explore details, cast, trailers and streaming options for ' . $title . ' on ' . SITE_NAME . '.';
$metaimg = tmdb_image($currentmovie['backdrop_path'] ?? $currentmovie['poster_path'] ?? '', 'w1280');
$primaryEmbed = tstream_embed_url('movietv', 'movie', $id);
$alternateEmbed = tstream_embed_url('fmov', 'movie', $id);
$trailerKey = '';
foreach (($currentmovie['videos']['results'] ?? []) as $video) {
    if (($video['site'] ?? '') === 'YouTube' && ($video['type'] ?? '') === 'Trailer') { $trailerKey = (string)$video['key']; break; }
}
$metaschema = json_ld([
    '@context' => 'https://schema.org',
    '@type' => 'Movie',
    '@id' => site_url(ltrim($path, '/')) . '#movie',
    'url' => site_url(ltrim($path, '/')),
    'name' => $title,
    'description' => $overview,
    'image' => tmdb_image($currentmovie['poster_path'] ?? '', 'w780'),
    'datePublished' => $currentmovie['release_date'] ?? null,
    'genre' => array_values(array_filter(array_map(static fn($g) => $g['name'] ?? null, $currentmovie['genres'] ?? []))),
    'duration' => !empty($currentmovie['runtime']) ? 'PT' . (int)$currentmovie['runtime'] . 'M' : null,
    'inLanguage' => 'en'
]);
$metaschema .= json_ld([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>site_url()],
        ['@type'=>'ListItem','position'=>2,'name'=>'Movies','item'=>site_url('movies')],
        ['@type'=>'ListItem','position'=>3,'name'=>$title,'item'=>site_url(ltrim($path,'/'))]
    ]
]);
require __DIR__ . '/includes/header.php';
?>
<main id="container" class="tstream-page">
  <div class="module"><div class="content tstream-watch-layout">
    <section class="watch-main">
      <div class="watch-heading"><div><span class="eyebrow">NOW PLAYING</span><h1><?= e($title) ?> <?= $year ? '<span class="muted">(' . e($year) . ')</span>' : '' ?></h1></div><span class="content-rating">★ <?= e(Ratingtwo($currentmovie['vote_average'] ?? 0)) ?></span></div>
      <div class="tstream-player-shell">
        <div class="play-video">
          <iframe id="player" src="<?= e($primaryEmbed) ?>" title="<?= e($title) ?> streaming player" allow="autoplay; fullscreen; picture-in-picture; encrypted-media" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="eager"></iframe>
        </div>
      </div>
      <div class="player-toolbar">
        <div class="player-sources" role="group" aria-label="Choose player source">
          <button type="button" class="player-source is-active" data-src="<?= e($primaryEmbed) ?>" aria-pressed="true">MovieTV Player <span>Recommended</span></button>
          <button type="button" class="player-source" data-src="<?= e($alternateEmbed) ?>" aria-pressed="false">FMOV Player</button>
        </div>
        <?php if ($trailerKey !== ''): ?><a class="trailer-link" href="<?= e('https://www.youtube.com/watch?v=' . rawurlencode($trailerKey)) ?>" target="_blank" rel="noopener noreferrer">▶ Watch trailer</a><?php endif; ?>
      </div>
      <div class="media-details">
        <div class="detail-poster"><img src="<?= e(tmdb_image($currentmovie['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($title) ?> poster" width="220" height="330" loading="lazy"></div>
        <div class="detail-copy">
          <div class="meta-pills">
            <?php if ($year): ?><span><?= e($year) ?></span><?php endif; ?>
            <?php if (!empty($currentmovie['runtime'])): ?><span><?= (int)$currentmovie['runtime'] ?> min</span><?php endif; ?>
            <span>★ <?= e(Ratingtwo($currentmovie['vote_average'] ?? 0)) ?>/10</span>
          </div>
          <ul class="genre"><?php foreach (($currentmovie['genres'] ?? []) as $genre): ?><li><?= e($genre['name'] ?? '') ?></li><?php endforeach; ?></ul>
          <h2>Overview</h2><p><?= e($overview !== '' ? $overview : 'No overview is available for this title yet.') ?></p>
          <?php if (!empty($currentmovie['credits']['cast'])): ?><h2>Cast</h2><p><?= e(implode(', ', array_slice(array_map(static fn($person) => (string)($person['name'] ?? ''), $currentmovie['credits']['cast']), 0, 8))) ?></p><?php endif; ?>
        </div>
      </div>
    </section>
    <aside class="watch-sidebar"><h2 class="widget-title">Trending movies</h2><div class="side-title-list">
      <?php foreach (array_slice($latestmovies['results'] ?? [], 0, 10) as $item): $itemId=(int)($item['id'] ?? 0); $itemTitle=(string)($item['title'] ?? 'Movie'); ?>
      <a class="side-title" href="/movies/<?= $itemId ?>/<?= e(slugify($itemTitle)) ?>"><img src="<?= e(tmdb_image($item['poster_path'] ?? '', 'w92')) ?>" alt="" width="54" height="81" loading="lazy"><span><?= e($itemTitle) ?><small>★ <?= e(Ratingtwo($item['vote_average'] ?? 0)) ?></small></span></a>
      <?php endforeach; ?>
    </div></aside>
  </div></div>
  <nav class="breadcrumb" aria-label="Breadcrumb"><ul><li><a href="/">Home</a></li><li><a href="/movies">Movies</a></li><li aria-current="page"><?= e($title) ?></li></ul></nav>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
