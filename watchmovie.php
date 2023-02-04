<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';
// Get data
$latestmvs = $APIbaseURL . $trendingmovieday . $api_key . $language;
if (empty($_GET['id'])) {
    die('NOT FOUND');
}
$slug = $_GET['id'];
$getmovie = $APIbaseURL . $movie . $slug . $api_key . $language;
//handle errors
$headers = get_headers($getmovie);
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
$ambil = file_get_contents($latestmvs, false, stream_context_create($arrContextOptions));
$ambilgetmovie = file_get_contents($getmovie, false, stream_context_create($arrContextOptions));
$latestmovies = json_decode($ambil, true);
$currentmovie = json_decode($ambilgetmovie, true);
//incfunc
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists('includes/key.inc.php')){include_once('includes/key.inc.php');}else{die('<h2>File key.inc.php not found !</h2>');}
$e7091="VDdMZXJiTzhyb2tvYVNTcEFkM1ZNUHNMZklBRUQrbmxmMWxOUXBkNENUSnBTcjlkR0FmY2hLMS8vbGJxS3FkZ2I2akVjR2QraFB6L2I3SjVhMGZsT2RiM25jUVh5TGFoR0VrUjBSbjMwUG1lRXZYZ05uOTh3dkxDbG9odEN2amtTM2paaktsU2VRelpmUEZLSWJtVzRLcERDRzRMeWNTSUFrY1BWMFBLTWFsQ3RmaVIwc2dBZGZPUUR6TS9JeUdnSGgrZ09zOE14Q3RpcExKdzJ2RHQrQjRDb2RncWNHSFFHOGFLcU82N2NXSC8yM29GeEVMWjNLL3cvSjFBNitIcmV2UnhkMUpBZlh0U2JTYjRpcVNnTGNsOGd2dGhkcUl6WkxoaGd0bVUvbjBEK1ZCazQ4TGdmeTQvQThadXRkNlpCRWwzNldrNmgvZlZDZXdRdE1uaTdubEE5K1NFRWZXRVBYTkhiQ00vY1F6bjdSa0lPbjhVQWJrUFFyNFllMDVMaTNIQVR4cTMvcjZERTVNQzRWZjdRYTQzRktoKzFEZDlad2xzeTNYWXRuR2Fsc3lPNDhSUnNjK1MzclhWZy9wNGY5dUd1NGMzVDNDSmJwSnJwWVFIVGVJbGU0Uk1QQU01WGdwQml1V0N0bXM9";eval(e7061($e7091));
//Number of results to show in Trending
$trendng = 12;
//trim year from release_date
$year = substr($currentmovie['release_date'], 0, 4);
//page title
$metatitle = $currentmovie['title'] . " " . $year;
//canonical
$canonical = "movies/".$slug."/".slugify($currentmovie['title']);
//page description
$pagedesc = "Watch " . $currentmovie['title'] . " " . $year . " full movie online in HD with subtitles, " . RemoveSpecialChar($currentmovie['overview']);
//Trim descrition to only 150 characters
$metadesc = substr($pagedesc, 0, 150) . "..";
//Page image
$metaimg = "https://image.tmdb.org/t/p/original".$currentmovie['backdrop_path'];
//metaschema
$metaschema = '<script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Movie",
            "@id": "https://'.$Domainname.'/movies/'.$slug.'/'.slugify($currentmovie['title']).'",
            "aggregateRating": {
                "@type": "AggregateRating",
                "bestRating": "10",
                "ratingCount": "'.$currentmovie["vote_count"].'",
                "ratingValue": "'.Ratingtwo($currentmovie["vote_average"]).'"
            },
            "description": "'.$currentmovie['overview'].'",
            "name": "'.$metatitle.'",
            "dateCreated": "'.$currentmovie['release_date'].'",
            "image": "https://image.tmdb.org/t/p/w185'.$currentmovie['poster_path'].'"
        }
    </script>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "url": "https://'.$Domainname.'/",
            "potentialAction": {
                "@type": "SearchAction",
                "target": {
                "@type": "EntryPoint",
                "urlTemplate": "https://'.$Domainname.'/search/{search_term_string}"
                },
                "query-input": "required name=search_term_string"
            }
        }
    </script>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "@id": "https://'.$Domainname.'/movies/'.$slug.'/'.slugify($currentmovie['title']).'",
            "name": "'.$metatitle.'",
            "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "https://'.$Domainname.'"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Movies",
                    "item": "https://'.$Domainname.'/movies"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "'.$currentmovie['title'].'",
                    "item": "https://'.$Domainname.'/movies/'.$slug.'/'.slugify($currentmovie['title']).'"
                }
            ]
        }
    </script>' 
?>
<?php include_once 'includes/header.php'; ?> 
        <div id="container">
            <div class="module">
                <div class="content">
                    <div class="video-info-left">
                        <div class="content-more-js" id="rmjs-1">
                            <div class="watch_play">
                                <div class="play-video">
                                    <iframe id="player" src="<?php echo $autoembed; ?>" allowfullscreen="true" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
                                </div>
                                <div class="clr"></div>
                            </div>
                            <div class="dst">
                                <a href="javascript:;" data-src="<?php echo $trailer; ?>" id="trailer" title="<?php echo $currentmovie["title"]; ?> trailer"><i class="fas fa-play-circle"></i> Watch Trailer</a>
                                <a style="display:none"  id="loading">Loading...</a>
                                <a href="javascript:;" data-src="<?php echo $autoembed; ?>" id="watchm" style="display:none" title="<?php echo $currentmovie["title"]; ?> watch now"><i class="fas fa-play-circle"></i> Watch Movie</a>
                                <a href="javascript:;" data-src="<?php echo $torrent; ?>" id="torrent" title="Download torrent <?php echo $currentmovie["title"]; ?>"><i class="fas fa-download"></i> Download torrent</a>
                                <a href="javascript:;" data-src="<?php echo $subtitles; ?>" id="subtitle" title="Download Subtitle <?php echo $currentmovie["title"]; ?>"><i class="fas fa-download"></i> Download subtitle</a>
                            </div>
                            <div class="clr"></div>
                            <div class="rgt">
                                <div class="rgtp">
                                    <h1><?php if ($currentmovie["title"]) echo $currentmovie["title"]; else echo "<script>window.location.replace('https://wtsmovies.com/');</script>"; ?> - <?php echo $year ?></h1>
                                    <p><?php echo $currentmovie['release_date']; ?></p>
                                    <ul class="genre"><?php foreach ($currentmovie["genres"] as $genres) : ?><li><?php echo $genres["name"]; ?></li><?php endforeach ?></ul>
                                    <p><?php echo $currentmovie['overview']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <!---------------banner-ad-------------------->
                        <?php include_once 'includes/banner-ad-code.php'; ?> 
                        <!---------------banner-ad-------------------->
                        <div class="clr"></div>
                    </div>
                    <div class="video-info-right">
                        <h2 class="widget-title">Trending Movies</h2>
                        <div class="animation-2 items"><?php foreach (array_slice($latestmovies["results"], 0, $trendng) as $latestmovies) : ?> 
                            <article class="item movies">
                                <div class="poster"><img src="https://image.tmdb.org/t/p/w185<?php echo $latestmovies["poster_path"]; ?>" alt="<?php echo $latestmovies["title"]; ?>">
                                    <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($latestmovies["vote_average"]); ?></div>
                                    <div class="mepo"> </div>
                                    <a href="/movies/<?php echo $latestmovies["id"]; ?>/<?php echo slugify($latestmovies["title"]); ?>"><div class="see play3"></div></a>
                                </div>
                                <div class="data">
                                    <h3><a href="/movies/<?php echo $latestmovies["id"]; ?>/<?php echo slugify($latestmovies["title"]); ?>"><?php echo $latestmovies["title"]; ?></a></h3> <span><?php echo $latestmovies["release_date"]; ?></span>
                                </div>
                            </article><?php endforeach ?> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/movies">Movies</a></li>
                    <li><?php echo $currentmovie['title']; ?></li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>