<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = slugged($_GET['id'] ?? '');
if ($id === '0') { http_response_code(404); require __DIR__ . '/404.php'; exit; }
$show = tmdb_request('/tv/' . $id, ['append_to_response'=>'credits,recommendations'], 21600);
if (!$show || empty($show['id'])) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$title = (string)($show['name'] ?? 'TV Series');
$slug = slugify($title);
$path = '/tv/' . $id . '/' . $slug;
$canonical = $path;
$metatitle = $title . ' - Seasons & Episodes | ' . SITE_NAME;
$overview = trim((string)($show['overview'] ?? ''));
$metadesc = $overview !== '' ? 'Browse seasons and episodes of ' . $title . '. ' . $overview : 'Explore all seasons and episodes of ' . $title . ' on ' . SITE_NAME . '.';
$metaimg = tmdb_image($show['backdrop_path'] ?? $show['poster_path'] ?? '', 'w1280');
$metaschema = json_ld([
 '@context'=>'https://schema.org','@type'=>'TVSeries',
 '@id'=>site_url(ltrim($path,'/')).'#series','url'=>site_url(ltrim($path,'/')),
 'name'=>$title,'description'=>$overview,
 'image'=>tmdb_image($show['poster_path'] ?? '', 'w780'),
 'datePublished'=>$show['first_air_date'] ?? null,
 'genre'=>array_values(array_filter(array_map(static fn($g)=>$g['name'] ?? null,$show['genres'] ?? []))),
 'numberOfSeasons'=>count($show['seasons'] ?? [])
]);
$metaschema .= json_ld([
 '@context'=>'https://schema.org','@type'=>'BreadcrumbList',
 'itemListElement'=>[
  ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>site_url()],
  ['@type'=>'ListItem','position'=>2,'name'=>'TV Shows','item'=>site_url('tv')],
  ['@type'=>'ListItem','position'=>3,'name'=>$title,'item'=>site_url(ltrim($path,'/'))]
 ]
]);
require __DIR__ . '/includes/header.php';
?>
<main id="container" class="tstream-page">
 <div class="module"><div class="content right full">
  <section class="show-detail">
   <div class="media-details">
    <div class="detail-poster"><img src="<?= e(tmdb_image($show['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($title) ?> poster" width="220" height="330" loading="lazy"></div>
    <div class="detail-copy">
     <span class="eyebrow">TV SERIES</span>
     <h1><?= e($title) ?></h1>
     <div class="meta-pills">
      <?php if (!empty($show['first_air_date'])): ?><span><?= e(substr((string)$show['first_air_date'],0,4)) ?></span><?php endif; ?>
      <span><?= count($show['seasons'] ?? []) ?> seasons</span>
      <?php if (isset($show['vote_average'])): ?><span>★ <?= e(Ratingtwo($show['vote_average'])) ?>/10</span><?php endif; ?>
     </div>
     <ul class="genre"><?php foreach (($show['genres'] ?? []) as $genre): ?><li><?= e($genre['name'] ?? '') ?></li><?php endforeach; ?></ul>
     <h2>Overview</h2><p><?= e($overview !== '' ? $overview : 'No overview is available for this series yet.') ?></p>
     <?php if (!empty($show['credits']['cast'])): ?><h2>Cast</h2><p><?= e(implode(', ', array_slice(array_map(static fn($person)=>(string)($person['name'] ?? ''),$show['credits']['cast']),0,8))) ?></p><?php endif; ?>
    </div>
   </div>
  </section>
  <h2 class="widget-title-se" id="seasons">Seasons</h2>
  <div class="animation-2 items full arch">
   <?php foreach (($show['seasons'] ?? []) as $season): $seasonNumber=(int)($season['season_number'] ?? 0); $seasonName=(string)($season['name'] ?? 'Season '.$seasonNumber); $episodeCount=(int)($season['episode_count'] ?? 0); ?>
    <article class="item">
     <div class="poster">
      <img src="<?= e(tmdb_image($season['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($seasonName) ?> poster" width="185" height="278" loading="lazy">
      <div class="rating"><?= $episodeCount ?> episodes</div>
      <?php if ($episodeCount > 0): ?><a href="/episodes/<?= (int)$id ?>-<?= $seasonNumber ?>-1/<?= e($slug) ?>-season-<?= $seasonNumber ?>-episode-1"><span class="see play3"></span></a><?php endif; ?>
     </div>
     <div class="data"><h3><?= e($seasonName) ?></h3><span><?= e($season['air_date'] ?? 'Release date unavailable') ?></span></div>
    </article>
   <?php endforeach; ?>
  </div>
 </div></div>
 <nav class="breadcrumb" aria-label="Breadcrumb"><ul><li><a href="/">Home</a></li><li><a href="/tv">TV Shows</a></li><li aria-current="page"><?= e($title) ?></li></ul></nav>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
