<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';

$current_page = max(1, min(500, (int)($_GET['page'] ?? 1)));
$paginationCanonical = true;

// Latest Update SUB
$topratedmovies = $APIbaseURL . $topratedmovie . $api_key . $language;
if (isset($_GET['page'])) {
    $topratedmovies = $APIbaseURL . $topratedmovie . $api_key . $language . "&page=" . $current_page;
}
//handle errors


$ambil = tmdb_fetch_url($topratedmovies);
$tprtdmvs = json_decode($ambil ?: '{}', true) ?: ['results' => []];
/*----meta---*/
$canonical = "top-rated-movies";
$metatitle = 'Top Rated Movies | TStream WATCH';
$metadesc = 'Browse highly rated movies and explore film information, release dates, genres and cast on TStream WATCH.';
?>
<?php include_once 'includes/header.php'; ?>
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <h1 class="Featured">Top Rated Movies</h1>
                    <div class="animation-2 items full arch"><?php foreach ($tprtdmvs["results"] as $data) : ?>
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
                        // $current_page was normalized before the TMDB request.
                        $nextpage = min(500, $current_page + 1);
                        $prevpage = $current_page - 1;

                        if ($current_page >= 2) {
                            $wrap .= "<li class='previous'><a href='?page=$prevpage' data-page='$prevpage'> < </a></li>";
                        }

                        for ($i = $current_page - 1; $i <= min(500, $current_page + 4); $i++) {
                            if ($i == 0) {
                                continue;
                            }
                            $active = "";
                            if ($i == $current_page) {
                                $active = "active";
                            }

                            $wrap .= "<li class='$active'><a href='?page=" . $i . "'>" . $i . "</a></li>";
                        }
                        if ($current_page < 500) { $wrap .= "<li class='next'><a href='?page=$nextpage' data-page='$nextpage' aria-label='Next page'>&gt;</a></li>"; }
                        echo $wrap . "</ul>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><!--breadcrumbs-start-->
            <div class="breadcrumb">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li>Top Rated Movies</li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>