<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$page = max(1, min(500, (int)($_GET['page'] ?? 1)));
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$data = tmdb_request('/tv/popular', ['page' => $page], 3600) ?? ['results' => []];
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach (($data['results'] ?? []) as $item): if (empty($item['id']) || empty($item['name'])) continue; $url = '/tv/' . (int)$item['id'] . '/' . slugify($item['name']); ?>
 <url><loc><?= e(site_url(ltrim($url, '/'))) ?></loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
<?php endforeach; ?>
</urlset>
