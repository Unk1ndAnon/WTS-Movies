<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=1800');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <sitemap><loc><?= e(site_url('page-sitemap.xml')) ?></loc></sitemap>
 <?php for ($page=1; $page<=5; $page++): ?>
 <sitemap><loc><?= e(site_url('movies-sitemap'.$page.'.xml')) ?></loc></sitemap>
 <sitemap><loc><?= e(site_url('tv-sitemap'.$page.'.xml')) ?></loc></sitemap>
 <?php endfor; ?>
</sitemapindex>
