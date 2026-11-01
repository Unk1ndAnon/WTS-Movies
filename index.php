<?php
include_once 'includes/config.php';
include_once 'includes/functions.php';
// Get data
$trendingmovies = $APIbaseURL . $trendingmovieweek . $api_key . $language;
//handle errors

$trendingtv = $APIbaseURL . $trendingtvweek . $api_key . $language;
//handle errors


$ambil = tmdb_fetch_url($trendingmovies);
$ambiltv = tmdb_fetch_url($trendingtv);
$trndngmvs = json_decode($ambil ?: '{}', true) ?: ['results' => []];
$trndngtv = json_decode($ambiltv ?: '{}', true) ?: ['results' => []];
/*----meta---*/
$metatitle = 'Watch Movies and TV Shows Online | TStream WATCH';
$metadesc = 'Discover trending movies, popular films and TV series. Explore new releases, ratings, cast details and episodes on TStream WATCH.';
?>
<?php include_once 'includes/header.php'; ?> 
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <div class="animation-2 items full">
                        <h1 class="Featured widget-title">Featured Movies <span><a href="/movies" class="see-all">See all</a></span></h1><?php foreach ($trndngmvs["results"] as $datamvs) : ?> 
                        <article class="item">
                            <div class="poster"><img src="<?php if ($datamvs["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$datamvs["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?= e($datamvs['original_title'] ?? '') ?>">
                                <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($datamvs["vote_average"]); ?></div>
                                <div class="mepo"> </div>
                                <a href="/movies/<?= (int)($datamvs['id'] ?? 0) ?>/<?php echo Slugify($datamvs["title"]); ?>">
                                    <div class="see play3"></div>
                                </a>
                            </div>
                            <div class="data">
                                <h3><a href="/movies/<?= (int)($datamvs['id'] ?? 0) ?>/<?php echo Slugify($datamvs["title"]); ?>"><?= e($datamvs['title'] ?? '') ?></a></h3> <span><?= e($datamvs['release_date'] ?? 'N/A') ?></span>
                            </div>
                        </article><?php endforeach ?> 
                    </div>
                    <div class="animation-2 items full">
                        <h2 class="Featured widget-title">Featured TV Shows <span><a href="/tv" class="see-all">See all</a></span></h2><?php foreach ($trndngtv["results"] as $datatv) : ?> 
                        <article class="item">
                            <div class="poster"><img src="<?php if ($datatv["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$datatv["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?= e($datatv['name'] ?? '') ?>">
                                <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($datatv["vote_average"]); ?></div>
                                <div class="mepo"> </div>
                                <a href="/tv/<?= (int)($datatv['id'] ?? 0) ?>/<?php echo Slugify($datatv["name"]); ?>">
                                    <div class="see play3"></div>
                                </a>
                            </div>
                            <div class="data">
                                <h3><a href="/tv/<?= (int)($datatv['id'] ?? 0) ?>/<?php echo Slugify($datatv["name"]); ?>"><?= e($datatv['name'] ?? '') ?></a></h3> <span><?= e($datatv['first_air_date'] ?? 'N/A') ?></span>
                            </div>
                        </article><?php endforeach ?> 
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>