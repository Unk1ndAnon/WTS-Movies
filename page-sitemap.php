<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=1800');
$pages = [
 '' => '1.0', 'movies' => '0.8', 'trending-movies' => '0.7',
 'top-rated-movies' => '0.7', 'tv' => '0.8', 'trending-tv' => '0.7',
 'top-rated-tv' => '0.7', 'privacy' => '0.2', 'terms' => '0.2', 'dmca' => '0.2'
];
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $path=>$priority): ?>
 <url><loc><?= e(site_url($path)) ?></loc><lastmod><?= gmdate('Y-m-d') ?></lastmod><changefreq><?= $path===''?'daily':'weekly' ?></changefreq><priority><?= e($priority) ?></priority></url>
<?php endforeach; ?>
</urlset>
