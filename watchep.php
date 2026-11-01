<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/stream-embed.php';

$id = slugged($_GET['id'] ?? '');
$seasonNumber = filter_var($_GET['season'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>0,'max_range'=>999]]);
$episodeNumber = filter_var($_GET['episode'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1,'max_range'=>9999]]);
if ($id === '0' || $seasonNumber === false || $seasonNumber === null || $episodeNumber === false || $episodeNumber === null) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$currenttv = tmdb_request('/tv/' . $id, ['append_to_response'=>'external_ids,credits'], 21600);
$seasonData = tmdb_request('/tv/' . $id . '/season/' . $seasonNumber, [], 21600);
if (!$currenttv || !$seasonData || empty($currenttv['id'])) { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$episodes = $seasonData['episodes'] ?? [];
$currentEpisode = null;
foreach ($episodes as $candidate) {
    if ((int)($candidate['episode_number'] ?? 0) === $episodeNumber) { $currentEpisode = $candidate; break; }
}
if (!$currentEpisode) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$latesttvs = tmdb_request('/trending/tv/day', [], 1800) ?? ['results'=>[]];
$showName = (string)($currenttv['name'] ?? 'TV Series');
$episodeName = (string)($currentEpisode['name'] ?? 'Episode ' . $episodeNumber);
$slug = slugify($showName);
$path = '/episodes/' . $id . '-' . $seasonNumber . '-' . $episodeNumber . '/' . $slug . '-season-' . $seasonNumber . '-episode-' . $episodeNumber;
$expectedEpisodeSlug = $slug . '-season-' . $seasonNumber . '-episode-' . $episodeNumber;
$requestedSlug = (string)($_GET['slug'] ?? '');
if ($requestedSlug !== $expectedEpisodeSlug) {
    header('Location: ' . site_url(ltrim($path, '/')), true, 301);
    exit;
}
$canonical = $path;
$ogtype = 'video.episode';
$metatitle = $showName . ' - Season ' . $seasonNumber . ' Episode ' . $episodeNumber . ': ' . $episodeName . ' | ' . SITE_NAME;
$overview = trim((string)($currentEpisode['overview'] ?? $currenttv['overview'] ?? ''));
$metadesc = $overview !== '' ? 'Watch ' . $showName . ' season ' . $seasonNumber . ' episode ' . $episodeNumber . '. ' . $overview : 'Watch ' . $episodeName . ', season ' . $seasonNumber . ' of ' . $showName . ' on ' . SITE_NAME . '.';
$metaimg = tmdb_image($currentEpisode['still_path'] ?? $currenttv['backdrop_path'] ?? $currenttv['poster_path'] ?? '', 'w1280');
$primaryEmbed = tstream_embed_url('movietv', 'tv', $id, (int)$seasonNumber, (int)$episodeNumber, 'english');
$alternateEmbed = tstream_embed_url('fmov', 'tv', $id, (int)$seasonNumber, (int)$episodeNumber);
$seasonEpisodes = array_values($episodes);
$currentIndex = 0;
foreach ($seasonEpisodes as $i=>$entry) if ((int)($entry['episode_number'] ?? 0) === $episodeNumber) { $currentIndex=$i; break; }
$previous = $seasonEpisodes[$currentIndex-1] ?? null;
$next = $seasonEpisodes[$currentIndex+1] ?? null;
$episodeUrl = static function($ep) use ($id,$seasonNumber,$slug) {
    if (!$ep) return '';
    $num=(int)($ep['episode_number'] ?? 0);
    return '/episodes/' . $id . '-' . $seasonNumber . '-' . $num . '/' . $slug . '-season-' . $seasonNumber . '-episode-' . $num;
};
$metaschema = json_ld([
    '@context'=>'https://schema.org','@type'=>'TVEpisode',
    '@id'=>site_url(ltrim($path,'/')).'#episode','url'=>site_url(ltrim($path,'/')),
    'name'=>$episodeName,'description'=>$overview,
    'episodeNumber'=>(int)$episodeNumber,'datePublished'=>$currentEpisode['air_date'] ?? null,
    'image'=>tmdb_image($currentEpisode['still_path'] ?? $currenttv['backdrop_path'] ?? '', 'w780'),
    'partOfSeason'=>['@type'=>'TVSeason','seasonNumber'=>(int)$seasonNumber],
    'partOfSeries'=>['@type'=>'TVSeries','name'=>$showName,'url'=>site_url('tv/'.$id.'/'.$slug)]
]);
$metaschema .= json_ld([
    '@context'=>'https://schema.org','@type'=>'BreadcrumbList',
    'itemListElement'=>[
      ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>site_url()],
      ['@type'=>'ListItem','position'=>2,'name'=>'TV Shows','item'=>site_url('tv')],
      ['@type'=>'ListItem','position'=>3,'name'=>$showName,'item'=>site_url('tv/'.$id.'/'.$slug)],
      ['@type'=>'ListItem','position'=>4,'name'=>'Season '.$seasonNumber.' Episode '.$episodeNumber,'item'=>site_url(ltrim($path,'/'))]
    ]
]);
require __DIR__ . '/includes/header.php';
?>
<main id="container" class="tstream-page">
 <div class="module"><div class="content tstream-watch-layout">
  <section class="watch-main">
   <div class="watch-heading"><div><span class="eyebrow"><?= e($showName) ?> · SEASON <?= (int)$seasonNumber ?></span><h1><?= e($episodeName) ?></h1><p class="episode-subtitle">Episode <?= (int)$episodeNumber ?><?= !empty($currentEpisode['air_date']) ? ' · ' . e($currentEpisode['air_date']) : '' ?></p></div><span class="content-rating">★ <?= e(Ratingtwo($currentEpisode['vote_average'] ?? 0)) ?></span></div>
   <div class="tstream-player-shell"><div class="play-video"><iframe id="player" src="<?= e($primaryEmbed) ?>" title="<?= e($showName . ' ' . $episodeName) ?> streaming player" allow="autoplay; fullscreen; picture-in-picture; encrypted-media" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="eager"></iframe></div></div>
   <div class="player-toolbar">
    <div class="player-sources" role="group" aria-label="Choose player source">
     <button type="button" class="player-source is-active" data-src="<?= e($primaryEmbed) ?>" aria-pressed="true">MovieTV Player <span>Recommended</span></button>
     <button type="button" class="player-source" data-src="<?= e($alternateEmbed) ?>" aria-pressed="false">FMOV Player</button>
    </div>
   </div>
   <div class="episode-navigation">
    <?php if ($previous): ?><a href="<?= e($episodeUrl($previous)) ?>" class="episode-nav-link">← Previous episode</a><?php else: ?><span></span><?php endif; ?>
    <a href="/tv/<?= (int)$id ?>/<?= e($slug) ?>" class="episode-nav-link all-episodes">All seasons</a>
    <?php if ($next): ?><a href="<?= e($episodeUrl($next)) ?>" class="episode-nav-link">Next episode →</a><?php else: ?><span></span><?php endif; ?>
   </div>
   <div class="media-details">
    <div class="detail-poster"><img src="<?= e(tmdb_image($currenttv['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($showName) ?> poster" width="220" height="330" loading="lazy"></div>
    <div class="detail-copy"><div class="meta-pills"><span>Season <?= (int)$seasonNumber ?></span><span>Episode <?= (int)$episodeNumber ?></span><span>★ <?= e(Ratingtwo($currentEpisode['vote_average'] ?? 0)) ?>/10</span></div>
     <ul class="genre"><?php foreach (($currenttv['genres'] ?? []) as $genre): ?><li><?= e($genre['name'] ?? '') ?></li><?php endforeach; ?></ul>
     <h2>Episode overview</h2><p><?= e($overview !== '' ? $overview : 'No episode overview is available yet.') ?></p>
     <?php if (!empty($currentEpisode['runtime'])): ?><p class="muted"><?= (int)$currentEpisode['runtime'] ?> minutes</p><?php endif; ?>
    </div>
   </div>
   <h2 class="widget-title-se">Season <?= (int)$seasonNumber ?> episodes</h2>
   <div class="episode-grid">
   <?php foreach ($seasonEpisodes as $ep): $epNum=(int)($ep['episode_number'] ?? 0); $epPath=$episodeUrl($ep); ?>
    <a class="episode-card <?= $epNum===(int)$episodeNumber ? 'current' : '' ?>" href="<?= e($epPath) ?>">
     <img src="<?= e(tmdb_image($ep['still_path'] ?? '', 'w300')) ?>" alt="" width="300" height="169" loading="lazy">
     <span><b><?= $epNum ?>. <?= e($ep['name'] ?? 'Episode '.$epNum) ?></b><small><?= e($ep['air_date'] ?? '') ?></small></span>
    </a>
   <?php endforeach; ?>
   </div>
  </section>
  <aside class="watch-sidebar"><h2 class="widget-title">Trending TV shows</h2><div class="side-title-list">
   <?php foreach (array_slice($latesttvs['results'] ?? [], 0, 10) as $item): $itemId=(int)($item['id'] ?? 0); $itemTitle=(string)($item['name'] ?? 'TV Series'); ?>
    <a class="side-title" href="/tv/<?= $itemId ?>/<?= e(slugify($itemTitle)) ?>"><img src="<?= e(tmdb_image($item['poster_path'] ?? '', 'w92')) ?>" alt="" width="54" height="81" loading="lazy"><span><?= e($itemTitle) ?><small>★ <?= e(Ratingtwo($item['vote_average'] ?? 0)) ?></small></span></a>
   <?php endforeach; ?>
  </div></aside>
 </div></div>
 <nav class="breadcrumb" aria-label="Breadcrumb"><ul><li><a href="/">Home</a></li><li><a href="/tv">TV Shows</a></li><li><a href="/tv/<?= (int)$id ?>/<?= e($slug) ?>"><?= e($showName) ?></a></li><li aria-current="page">S<?= (int)$seasonNumber ?> E<?= (int)$episodeNumber ?></li></ul></nav>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
