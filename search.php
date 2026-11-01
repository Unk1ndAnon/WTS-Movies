<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$keyword = trim((string)($_GET['keyword'] ?? ''));
if (function_exists('mb_substr')) $keyword = mb_substr($keyword, 0, 100, 'UTF-8');
else $keyword = substr($keyword, 0, 100);
$page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options'=>['default'=>1,'min_range'=>1,'max_range'=>500]]);
$movies = $keyword !== '' ? (tmdb_request('/search/movie', ['query'=>$keyword,'page'=>$page,'include_adult'=>'false'], 180) ?? ['results'=>[],'total_pages'=>1]) : ['results'=>[],'total_pages'=>1];
$shows = $keyword !== '' ? (tmdb_request('/search/tv', ['query'=>$keyword,'page'=>$page], 180) ?? ['results'=>[],'total_pages'=>1]) : ['results'=>[],'total_pages'=>1];
$metatitle = 'Search results for ' . $keyword . ' - ' . SITE_NAME;
$metadesc = 'Search movies and TV shows on ' . SITE_NAME . '.';
$robots = 'noindex,follow';
require __DIR__ . '/includes/header.php';
?>
<main id="container" class="tstream-page"><div class="module"><div class="content right full">
<h1 class="heading-archive">Search results for <?= e($keyword) ?></h1>
<?php if ($keyword === ''): ?><p class="empty-state">Enter a movie or TV show title in the search bar.</p><?php else: ?>
<section class="animation-2 items full arch"><h2 class="Featured">Movies</h2>
<?php foreach (($movies['results'] ?? []) as $item): if (empty($item['id'])) continue; $title=(string)($item['title'] ?? 'Movie'); $url='/movies/'.(int)$item['id'].'/'.slugify($title); ?>
<article class="item"><div class="poster"><img src="<?= e(tmdb_image($item['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($title) ?>" width="185" height="278" loading="lazy"><div class="rating">★ <?= e(Ratingtwo($item['vote_average'] ?? 0)) ?></div><a href="<?= e($url) ?>"><span class="see play3"></span></a></div><div class="data"><h3><a href="<?= e($url) ?>"><?= e($title) ?></a></h3><span><?= e($item['release_date'] ?? 'N/A') ?></span></div></article>
<?php endforeach; ?>
</section>
<section class="animation-2 items full arch"><h2 class="Featured">TV Shows</h2>
<?php foreach (($shows['results'] ?? []) as $item): if (empty($item['id'])) continue; $title=(string)($item['name'] ?? 'TV Show'); $url='/tv/'.(int)$item['id'].'/'.slugify($title); ?>
<article class="item"><div class="poster"><img src="<?= e(tmdb_image($item['poster_path'] ?? '', 'w342')) ?>" alt="<?= e($title) ?>" width="185" height="278" loading="lazy"><div class="rating">★ <?= e(Ratingtwo($item['vote_average'] ?? 0)) ?></div><a href="<?= e($url) ?>"><span class="see play3"></span></a></div><div class="data"><h3><a href="<?= e($url) ?>"><?= e($title) ?></a></h3><span><?= e($item['first_air_date'] ?? 'N/A') ?></span></div></article>
<?php endforeach; ?>
</section>
<?php if (empty($movies['results']) && empty($shows['results'])): ?><p class="empty-state">No results found for “<?= e($keyword) ?>”. Try another title.</p><?php endif; ?>
<?php endif; ?>
</div></div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
