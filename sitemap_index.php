<?php header('Content-type: application/xml; charset=utf-8')?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<?php echo '<?xml-stylesheet type="text/xsl" href="/CSS/sitemap.xsl"?>'?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/page-sitemap.xml</loc>
    </sitemap>
<?php for($n=1; $n<=500; $n++):?>
    <sitemap>
        <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/movies-sitemap<?php echo $n; ?>.xml</loc>
    </sitemap>
<?php endfor;?>
<?php for($n=1; $n<=500; $n++):?>
    <sitemap>
        <loc>https://<?php echo $_SERVER['SERVER_NAME'] ?>/tv-sitemap<?php echo $n; ?>.xml</loc>
    </sitemap>
<?php endfor;?>
</sitemapindex>