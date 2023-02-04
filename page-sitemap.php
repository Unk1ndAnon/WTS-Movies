<?php header('Content-type: application/xml; charset=utf-8')?>
<?php echo'<?xml version="1.0" encoding="UTF-8"?>'?>
<?php echo'<?xml-stylesheet type="text/xsl" href="/CSS/sitemap.xsl"?>'?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>1.0000</priority>
</url>
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/movies</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>0.8000</priority>
</url>
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/trending-movies</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>0.8000</priority>
</url>
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/top-rated-movies</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>0.8000</priority>
</url>
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/tv</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>0.8000</priority>
</url>
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/trending-tv</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>0.8000</priority>
</url>
<url>
     <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/top-rated-tv</loc>
     <lastmod>2022-02-09T09:29:03+00:00</lastmod>
     <changefreq>daily</changefreq>
     <priority>0.8000</priority>
</url>
</urlset>