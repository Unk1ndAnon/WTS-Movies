<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Latest Update SUB
$trendingmovies = $APIbaseURL . $trendingmovieweek . $api_key . $language;
if (isset($_GET['page'])) {
    $trendingmovies = $APIbaseURL . $trendingmovieweek . $api_key . $language . "&page=" . $_GET['page'];
}
//handle errors


$ambil = tmdb_fetch_url($trendingmovies);
$trndngmvs = json_decode($ambil, true);
/*----meta---*/
$canonical = "trending-movies";
$metatitle = 'Trending Movies This Week | TStream WATCH';
$metadesc = 'Explore movies trending this week, discover popular releases and browse film information on TStream WATCH.';
?>
<?php include_once 'includes/header.php'; ?>
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <h1 class="Featured">Trending Movies</h1>
                    <div class="animation-2 items full arch"><?php foreach ($trndngmvs["results"] as $data) : ?>
                        <article class="item">
                            <div class="poster"><img src="<?php if ($data["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$data["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?= e($data['title'] ?? '') ?>">
                                <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($data["vote_average"]); ?></div>
                                <div class="mepo"> </div>
                                <a href="/movies/<?= (int)($data['id'] ?? 0) ?>/<?php echo slugify($data["title"]); ?>">
                                    <div class="see play3"></div>
                                </a>
                            </div>
                            <div class="data">
                                <h3><a href="/movies/<?= (int)($data['id'] ?? 0) ?>/<?php echo slugify($data["title"]); ?>"><?= e($data['title'] ?? '') ?></a></h3> <span><?= e($data['release_date'] ?? 'N/A') ?></span>
                            </div>
                        </article><?php endforeach ?>
                    </div>
                    <div class="pagination">
                        <?php
                        $wrap = "<ul class='pagination'>";
                        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
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
                    <li>Trending Movies</li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>