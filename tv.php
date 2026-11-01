<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';

$populartv = $APIbaseURL . $tv . $popular . $api_key . $language;
if (isset($_GET['page'])) {
    $populartv = $APIbaseURL . $tv . $popular . $api_key . $language . "&page=" . max(1, min(500, (int)($_GET['page'] ?? 1)));
}

$ambil = tmdb_fetch_url($populartv);
$pplrtv = json_decode($ambil, true);
/*----meta---*/
$canonical = "tv";
$metatitle = 'Popular TV Shows and Series | TStream WATCH';
$metadesc = 'Explore popular TV shows and series, discover seasons and episodes, and find your next show to watch on TStream WATCH.';
?>
<?php include_once 'includes/header.php'; ?>
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <h1 class="Featured">TV Shows</h1>
                    <div class="animation-2 items full arch"><?php foreach ($pplrtv["results"] as $data) : ?>
                        <article class="item">
                            <div class="poster"><img src="<?php if ($data["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$data["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?= e($data['name'] ?? '') ?>">
                                <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($data["vote_average"]); ?></div>
                                <div class="mepo"> </div>
                                <a href="/tv/<?= (int)($data['id'] ?? 0) ?>/<?php echo Slugify($data["name"]); ?>">
                                    <div class="see play3"></div>
                                </a>
                            </div>
                            <div class="data">
                                <h3><a href="/tv/<?= (int)($data['id'] ?? 0) ?>/<?php echo Slugify($data["name"]); ?>"><?= e($data['name'] ?? '') ?></a></h3> <span><?= e($data['first_air_date'] ?? 'N/A') ?></span>
                            </div>
                        </article><?php endforeach ?>
                    </div>
                    <div class="pagination">
                        <?php
                        $wrap = "<ul class='pagination'>";
                        $current_page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options'=>['default'=>1,'min_range'=>1,'max_range'=>500]]);
                        $nextpage = $current_page + 1;
                        $prevpage = $current_page - 1;

                        if ($current_page >= 2) {
                            $wrap .= "<li class='previous'><a href='?page=$prevpage' data-page='$prevpage'> < </a></li>";
                        }

                        for ($i = $current_page - 1; $i <= $current_page + 4; $i++) {
                            if ($i == 0) {
                                continue;
                            }
                            $active = "";
                            if ($i == $current_page) {
                                $active = "active";
                            }

                            $wrap .= "<li class='$active'><a href='?page=" . $i . "'>" . $i . "</a><li>";
                        }
                        echo $wrap . "<li class='next'><a href='?page=$nextpage' data-page='$nextpage'> > </a></li></ul>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><!--breadcrumbs-start-->
            <div class="breadcrumb">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li>TV Shows</li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>