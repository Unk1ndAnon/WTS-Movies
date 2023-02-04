<?php
include_once 'includes/config.php';
include_once 'includes/functions.php';
// Get data
$trendingmovies = $APIbaseURL . $trendingmovieweek . $api_key . $language;
//handle errors
$headers = get_headers($trendingmovies);
if (stripos($headers[0], '40') !== false || stripos($headers[0], '50') !== false) {
    include '404.php';
    exit();
}
$trendingtv = $APIbaseURL . $trendingtvweek . $api_key . $language;
//handle errors
$headers = get_headers($trendingtv);
if (stripos($headers[0], '40') !== false || stripos($headers[0], '50') !== false) {
    include '404.php';
    exit();
}
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);
$ambil = file_get_contents($trendingmovies, false, stream_context_create($arrContextOptions));
$ambiltv = file_get_contents($trendingtv, false, stream_context_create($arrContextOptions));
$trndngmvs = json_decode($ambil, true);
$trndngtv = json_decode($ambiltv, true);
/*----meta---*/
$metatitle = $SiteTitle.'- Watch Movies and TV series online for free';
$metadesc = 'Watch and download latest movies and TV Shows for free in HD streaming with multiple language subtitles.';
?>
<?php include_once 'includes/header.php'; ?> 
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <div class="animation-2 items full">
                        <h1 class="Featured widget-title">Featured Movies <span><a href="/movies" class="see-all">See all</a></span></h1><?php foreach ($trndngmvs["results"] as $datamvs) : ?> 
                        <article class="item">
                            <div class="poster"><img src="<?php if ($datamvs["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$datamvs["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?php echo $datamvs["original_title"]; ?>">
                                <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($datamvs["vote_average"]); ?></div>
                                <div class="mepo"> </div>
                                <a href="/movies/<?php echo $datamvs["id"]; ?>/<?php echo Slugify($datamvs["title"]); ?>">
                                    <div class="see play3"></div>
                                </a>
                            </div>
                            <div class="data">
                                <h3><a href="/movies/<?php echo $datamvs["id"]; ?>/<?php echo Slugify($datamvs["title"]); ?>"><?php echo $datamvs["title"]; ?></a></h3> <span><?php if ($datamvs["release_date"]) echo $datamvs["release_date"]; else echo "N/A"; ?></span>
                            </div>
                        </article><?php endforeach ?> 
                    </div>
                    <div class="animation-2 items full">
                        <h2 class="Featured widget-title">Featured TV Shows <span><a href="/tv" class="see-all">See all</a></span></h2><?php foreach ($trndngtv["results"] as $datatv) : ?> 
                        <article class="item">
                            <div class="poster"><img src="<?php if ($datatv["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$datatv["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?php echo $datatv["name"]; ?>">
                                <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($datatv["vote_average"]); ?></div>
                                <div class="mepo"> </div>
                                <a href="/tv/<?php echo $datatv["id"]; ?>/<?php echo Slugify($datatv["name"]); ?>">
                                    <div class="see play3"></div>
                                </a>
                            </div>
                            <div class="data">
                                <h3><a href="/tv/<?php echo $datatv["id"]; ?>/<?php echo Slugify($datatv["name"]); ?>"><?php echo $datatv["name"]; ?></a></h3> <span><?php if ($datatv["first_air_date"]) echo $datatv["first_air_date"]; else echo "N/A"; ?></span>
                            </div>
                        </article><?php endforeach ?> 
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>