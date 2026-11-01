<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';

$getseasons = $_GET['id'];
$gtseasons = $APIbaseURL . $tv . $getseasons . $api_key . $language;

$ambil = tmdb_fetch_url($gtseasons);
$shwseason = json_decode($ambil, true);
/*----meta---*/
$canonical = "tv/".$getseasons."/".slugify($shwseason["name"]);
$metatitle = 'TV Show Seasons | TStream WATCH';
$metadesc = 'Explore every season and episode of your favorite TV series on TStream WATCH.';
?>
<?php include_once 'includes/header.php'; ?>
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <h1 class="Featured"><?= e($shwseason['name'] ?? '') ?> - Seasons</h1>
                    <div class="animation-2 items full arch"><?php foreach ($shwseason["seasons"] as $data) : ?>
                            <article class="item">
                                <div class="poster"><img src="<?php if ($data["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$data["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?= e($data['name'] ?? '') ?>">
                                    <div class="rating">Episodes : <?= e($data['episode_count'] ?? '') ?></div>
                                    <div class="mepo"> </div>
                                    <a href="/episodes/<?php echo $getseasons; ?>-<?= e($data['season_number'] ?? '') ?>-1/<?php echo slugify($shwseason["name"]); ?>-season-<?= e($data['season_number'] ?? '') ?>-episode-1">
                                        <div class="see play3"></div>
                                    </a>
                                </div>
                                <div class="data">
                                    <h3><a href="/episodes/<?php echo $getseasons; ?>-<?= e($data['season_number'] ?? '') ?>-1/<?php echo slugify($shwseason["name"]); ?>-season-<?= e($data['season_number'] ?? '') ?>-episode-1"><?= e($data['name'] ?? '') ?></a></h3> <span><?php if ($data["air_date"]) echo $data["air_date"]; else echo "N/A"; ?></span>
                                </div>
                            </article><?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><!--breadcrumbs-start-->
            <div class="breadcrumb">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/tv">TV Shows</a></li>
                    <li><?php echo $shwseason['name']; ?></li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>