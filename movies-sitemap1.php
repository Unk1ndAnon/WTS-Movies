<?php header('Content-type: application/xml; charset=utf-8')?>
<?php echo'<?xml version="1.0" encoding="UTF-8"?>'?>
<?php echo'<?xml-stylesheet type="text/xsl" href="/CSS/sitemap.xsl"?>'?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php
function slugged($res)
{
    // replace non digits by -
    $res = preg_replace('/[^0-9]/', '', $res);

    if (empty($res)) {
        return 'n-a';
    }

    return $res;
}
?>
<?php
$baseurl = $_SERVER['SERVER_NAME'];
function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
?>
<?php
$popularmovies = "https://api.themoviedb.org/3/movie/popular?api_key=8d6d91941230817f7807d643736e8a49&language=en-US&page=" . slugged($_SERVER['REQUEST_URI']);
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);
$ambil = file_get_contents($popularmovies, false, stream_context_create($arrContextOptions));
$pplrmvs = json_decode($ambil, true);
?>
<?php foreach ($pplrmvs["results"] as $data) : ?>
	<url>
		<loc>https://<?php echo $baseurl; ?>/movies/<?php echo $data["id"]; ?>/<?php echo slugify($data["title"]); ?></loc>
        <changefreq>daily</changefreq>
        <priority>1</priority>
	</url>
<?php endforeach ?>
</urlset>