<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';

$current_page = max(1, min(500, (int)($_GET['page'] ?? 1)));
$paginationCanonical = true;

// Load this catalogue through the shared TMDB client and private cache.
$catalogueLanguage = 'en-US';
if (isset($language) && is_string($language)) {
    $languageParams = [];
    parse_str(ltrim($language, '&?'), $languageParams);
    if (
        !empty($languageParams['language']) &&
        is_string($languageParams['language']) &&
        preg_match('/^[a-z]{2}(?:-[A-Z]{2})?$/', $languageParams['language'])
    ) {
        $catalogueLanguage = $languageParams['language'];
    }
}

$catalogueData = tmdb_request(
    '/tv/top_rated',
    ['language' => $catalogueLanguage, 'page' => $current_page],
    1800
) ?? [];

$tprtdtv = [
    'results' => is_array($catalogueData['results'] ?? null)
        ? $catalogueData['results']
        : []
];

/*----meta---*/
$canonical = "top-rated-tv";
$metatitle = 'Top Rated TV Shows | TStream WATCH';
$metadesc = 'Browse highly rated TV shows and series, with information about seasons, episodes and cast on TStream WATCH.';
?>

<?php include_once 'includes/header.php'; ?>
        <div id="container">
            <div class="module">
                <div class="content right full">
                    <h1 class="Featured">Top rated Tv-Shows</h1>
                    <div class="animation-2 items full arch"><?php foreach ($tprtdtv["results"] as $data) : ?>
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
                    <li>Top rated Tv Shows</li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>