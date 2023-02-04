<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';

$getseasons = $_GET['id'];
$gtseasons = $APIbaseURL . $tv . $getseasons . $api_key . $language;
$arrContextOptions = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);
$ambil = file_get_contents($gtseasons, false, stream_context_create($arrContextOptions));
$shwseason = json_decode($ambil, true);
/*----meta---*/
$canonical = "tv/".$getseasons."/".slugify($shwseason["name"]);
$metatitle = $SiteTitle.'- Watch Movies and TV series online for free';
$metadesc = 'Watch and download latest movies and TV Shows for free in HD streaming with multiple language subtitles.';
?>
<?php include_once 'includes/header.php'; ?>
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <h1 class="Featured"><?php echo $shwseason["name"]; ?> - Seasons</h1>
                    <div class="animation-2 items full arch"><?php foreach ($shwseason["seasons"] as $data) : ?>
                            <article class="item">
                                <div class="poster"><img src="<?php if ($data["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$data["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?php echo $data["name"]; ?>">
                                    <div class="rating">Episodes : <?php echo $data["episode_count"]; ?></div>
                                    <div class="mepo"> </div>
                                    <a href="/episodes/<?php echo $getseasons; ?>-<?php echo $data["season_number"]; ?>-1/<?php echo slugify($shwseason["name"]); ?>-season-<?php echo $data["season_number"]; ?>-episode-1">
                                        <div class="see play3"></div>
                                    </a>
                                </div>
                                <div class="data">
                                    <h3><a href="/episodes/<?php echo $getseasons; ?>-<?php echo $data["season_number"]; ?>-1/<?php echo slugify($shwseason["name"]); ?>-season-<?php echo $data["season_number"]; ?>-episode-1"><?php echo $data["name"]; ?></a></h3> <span><?php if ($data["air_date"]) echo $data["air_date"]; else echo "N/A"; ?></span>
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