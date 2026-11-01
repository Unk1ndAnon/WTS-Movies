<?php
http_response_code(404);
require_once __DIR__ . '/includes/config.php';
$metatitle = '404 - Page not found | ' . SITE_NAME;
$metadesc = 'The page you requested could not be found on ' . SITE_NAME . '.';
$robots = 'noindex,follow';
require __DIR__ . '/includes/header.php';
?>
<main id="container" class="tstream-page"><div class="module"><div class="content right full">
<h1 class="heading-archive">Page not found</h1>
<div class="animation-2 items full empty-state"><h2>ERROR <span>404</span></h2><p>We could not find the page you requested.</p><p><a href="/">Return to TStream WATCH</a> or use search to find a movie or TV show.</p></div>
</div></div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>
