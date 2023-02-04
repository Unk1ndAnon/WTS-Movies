<?php
//include files
include_once 'includes/config.php';
include_once 'includes/functions.php';
//Get data
$latesttv = $APIbaseURL . $trendingtvday . $api_key . $language;
if (empty($_GET['id'])) {
    die('NOT FOUND');
}
$getid = $_GET['id'];
$getseason = $_GET['season'];
$getepisode = $_GET['episode'];
$gettv =  $APIbaseURL . $tv . $getid . $api_key . $language . "&append_to_response=external_ids";
//handle errors
$headers = get_headers($gettv);
if (stripos($headers[0], '40') !== false || stripos($headers[0], '50') !== false) {
    include '404.php';
    exit();
}
$getepisodes =  $APIbaseURL . $tv . $getid . "/season/" . $getseason . $api_key . $language;
//handle errors
$headers = get_headers($getepisodes);
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
$ambil = file_get_contents($latesttv, false, stream_context_create($arrContextOptions));
$ambilgettv = file_get_contents($gettv, false, stream_context_create($arrContextOptions));
$ambilgetepisodes = file_get_contents($getepisodes, false, stream_context_create($arrContextOptions));
$latesttvs = json_decode($ambil, true);
$currenttv = json_decode($ambilgettv, true);
$currenttvepisodes = json_decode($ambilgetepisodes, true);
//incfunc
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists('includes/key.inc.php')){include_once('includes/key.inc.php');}else{die('<h2>File key.inc.php not found !</h2>');}
$e7091="VDdMZXJiTzhyb2tvYVNTcEFkM1ZNUHNMZklBRUQrbmxmMWxOUXBkNENUSjJqVklqV0trbkJRck5wZnRSR1c0RVp3SDd6T1RTT3hlRy9QUEZtNGFNcWdnUlNzN1l5dUpzM0pxT1N0VGZySWx5YjhUeXVTUGdKT3RmQjhLVytCdzRucWpuRXIzSk5wNkFCNFNzTVNHc045ZEhtOUQyVG9aTTBvaGgzdThvdldmMlRWbERsNUpULzRtQ1ZZTU01Q1dXN2dJZEhYUldoc0pMcWR1VjZ1YUdvbDE1MC94Uk0zZUNtbEVkZFR1eHFUMEg1bUE1TVVhMEJJRUY3L3B4RVdHeDd2QXkrZDBTNGtzVEpDSnJ4MDk0SzdGSWZEUVhLb2d2ZTl2Y0YySXFWRHR4NGh4Qkw4MytrZVJoZHZ2RFhTWGY1L3ZDcmNtcFE3YnVhTXZnUmtRQU5TaVRQclhLcGVMOEp6SXdvVlpROThrY3dCRThORjl0Qk1Vc1JQZ0FTbFBtNEtNYktoaHFsZzh0RTR0MW5iQ1pBekRwOGlHYmhKK0ppNEZIWlZsbjlwMjcwUUFkSTVvVEVqOHA3VnVRUWNkb2JRSzRuYzk4clA3Q21aanIyektzNDNCRnNKMUJGSzlJbEpNTkc3bGNwekxzWVo5ZkpSN0p3RFRwdVRpY1FhdExvRUV2VFlweUhoNlp5R0RVS29OT2d6Z2d4OXdFWmFZQjFOMGZXb0NCbzZYZDdMTG91bjJ4STluZ3BrQnVhNXhpeCtMRXdxQmkrSERaNnRxa3EwSVRVbWcvejBtRlJreThQR0JZdzdDTUNYdGs3V1RrYUlUV05CTmtIWWtXOW5raGFBWXo5cU5IWjA2N25OdHFKV3I2SEtpaHNnRis4RTBtYnNwaTgzYmlGdFYrSEpwRXg0RzFjRklIRVdSbmZ3T1Nwd3IrVCtlOGRyZkJxN2plbzJnZDVjTlJlSzdJUU1FaFpMTnZTRFlBLzJnPQ==";eval(e7061($e7091));
//Number of results to show in Trending
$trendng = 18;
//page title
$metatitle = "Watch " . $currenttv['name'] . " - season " . $getseason . " Episode " . $getepisode . " online";
//canonical
$canonical = "episodes/".$getid."-".$getseason."-".$getepisode."/".slugify($currenttv['name'])."-season-".$getseason."-episode-".$getepisode;
//page description
$dde = RemoveSpecialChar($currenttv['overview']);
$pagedesc = "Watch " . $currenttv['name'] . " - season " . $getseason . " Episode " . $getepisode . " in HD with subtitles, " . $dde;
//Trim descrition to only 150 characters
$metadesc = substr($pagedesc, 0, 150) . "..";
//Page image
$metaimg = "https://image.tmdb.org/t/p/original".$currenttv['backdrop_path'];
//metaschema
$metaschema = '<script type="application/ld+json">
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
            "@id": "https://'.$Domainname.'/tv/'.$getid.'/'.slugify($currenttv['name']).'",
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
                    "name": "tv",
                    "item": "https://'.$Domainname.'/tv"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "'.$currenttv['name'].'",
                    "item": "https://'.$Domainname.'/tv/'.$getid.'/'.slugify($currenttv['name']).'"
                },
                {
                    "@type": "ListItem",
                    "position": 4,
                    "name": "Season '.$getseason.' Episode '.$getepisode.'",
                    "item": "https://'.$Domainname.'/episodes/'.$getid.'-'.$getseason.'-'.$getepisode.'/'.slugify($currenttv['name']).'-season-'.$getseason.'-episode-'.$getepisode.'"
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
                        <div class="post-entry">
                            <div class="content-more-js" id="rmjs-1">
                                <div class="watch_play">
                                    <div class="play-video">
                                        <iframe id="player" src="<?php echo $autoembed; ?>" allowfullscreen="true" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="dst">
                                    <a href="javascript:;" data-src="<?php echo $trailer; ?>" id="trailer" title="<?php echo $currenttv["name"]; ?> trailer"><i class="fas fa-play-circle"></i> Watch Trailer</a>
                                    <a style="display:none"  id="loading">Loading...</a>
                                    <a href="javascript:;" data-src="<?php echo $autoembed; ?>" id="watchm" style="display:none" title="<?php echo $currenttv["name"]; ?> watch now"><i class="fas fa-play-circle"></i> Watch Episode</a>
                                    <a href="javascript:;" data-src="<?php echo $torrent; ?>" id="torrent" title="Download torrent <?php echo $currenttv["name"]; ?>"><i class="fas fa-download"></i> Download torrent</a>
                                    <a href="javascript:;" data-src="<?php echo $subtitles; ?>" id="subtitle" title="Download Subtitle <?php echo $currenttv["name"]; ?>"><i class="fas fa-download"></i> Download subtitle</a>
                                </div>
                                <div class="clr"></div>
                                <div class="rgt">
                                    <div class="rgtp">
                                        <h1><?php echo $currenttv['name']; ?> - Season <?php echo $getseason; ?> : Episode <?php echo $getepisode; ?></h1>
                                        <p><?php echo $currenttvepisodes['air_date']; ?></p>
                                        <ul class="genre"><?php foreach ($currenttv["genres"] as $genres) : ?><li><?php echo $genres["name"]; ?></li><?php endforeach ?></ul>
                                        <p><?php if ($currenttvepisodes["overview"]) echo $currenttvepisodes["overview"]; else echo $currenttv['overview']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <!--------------Episodes----------------->
                        <h2 class="widget-title-se">Season <?php echo $getseason; ?> Episodes</h2>
                        <div class="animation-2 items full"><?php foreach ($currenttvepisodes["episodes"] as $currenttvepisodes) : ?> 
                            <article class="iteme">
                                <div class="posterm"><img src="<?php if ($currenttvepisodes["still_path"]) echo "https://image.tmdb.org/t/p/w185".$currenttvepisodes["still_path"]; else echo "/img/nobackdrop.png"; ?>" alt="<?php echo $currenttvepisodes["name"]; ?>">
                                    <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($currenttvepisodes["vote_average"]); ?></div>
                                    <div class="mepo"> </div>
                                    <a href="/episodes/<?php echo $getid; ?>-<?php echo $getseason; ?>-<?php echo $currenttvepisodes["episode_number"]; ?>/<?php echo slugify($currenttv["name"]); ?>-season-<?php echo $getseason; ?>-episode-<?php echo $currenttvepisodes["episode_number"]; ?>">
                                        <div class="see play3"></div>
                                    </a>
                                </div>
                                <div class="data">
                                    <h3><a href="/episodes/<?php echo $getid; ?>-<?php echo $getseason; ?>-<?php echo $currenttvepisodes["episode_number"]; ?>/<?php echo slugify($currenttv["name"]); ?>-season-<?php echo $getseason; ?>-episode-<?php echo $currenttvepisodes["episode_number"]; ?>">Episode <?php echo $currenttvepisodes["episode_number"]; ?></a></h3>
                                    <span><?php if ($currenttvepisodes["air_date"]) echo $currenttvepisodes["air_date"]; else echo "N/A"; ?></span>
                                </div>
                            </article><?php endforeach ?> 
                        </div> 
                        <div class="clr"></div>
                        <!--------------Seasons----------------->
                        <h2 class="widget-title-se" id="seasons">Seasons</h2>
                        <div class="animation-2 items bbt"><?php foreach ($currenttv["seasons"] as $data) : ?> 
                            <article class="item">
                                <div class="poster"><img src="<?php if ($data["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$data["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?php echo $data["name"]; ?>">
                                    <div class="rating">Episodes : <?php echo $data["episode_count"]; ?></div>
                                    <div class="mepo"> </div>
                                    <a href="/episodes/<?php echo $getid; ?>-<?php echo $data["season_number"]; ?>-1/<?php echo slugify($currenttv["name"]); ?>-season-<?php echo $data["season_number"]; ?>-episode-1">
                                        <div class="see play3"></div>
                                    </a>
                                </div>
                                <div class="data">
                                    <h3><a href="/episodes/<?php echo $getid; ?>-<?php echo $data["season_number"]; ?>-1/<?php echo slugify($currenttv["name"]); ?>-season-<?php echo $data["season_number"]; ?>-episode-1"><?php echo $data["name"]; ?></a></h3> <span><?php if ($data["air_date"]) echo $data["air_date"]; else echo "N/A"; ?></span>
                                </div>
                            </article><?php endforeach ?> 
                        </div>
                        <div class="clr"></div>
                        <!---------------banner-ad-------------------->
                        <?php include_once 'includes/banner-ad-code.php'; ?>
                        <!---------------banner-ad-------------------->
                        <div class="clr"></div>
                    </div>
                    <div class="video-info-right">
                        <h2 class="widget-title">Trending TV shows</h2>
                        <div class="animation-2 items"><?php foreach (array_slice($latesttvs["results"], 0, $trendng) as $latesttvs) : ?> 
                            <article class="item">
                                <div class="poster"><img src="<?php if ($latesttvs["poster_path"]) echo "https://image.tmdb.org/t/p/w185".$latesttvs["poster_path"]; else echo "/img/noposter.png"; ?>" alt="<?php echo $latesttvs["name"]; ?>">
                                    <div class="rating"><i class="fa fa-star"></i> <?php echo Ratingtwo($latesttvs["vote_average"]); ?></div>
                                    <div class="mepo"> </div>
                                    <a href="/tv/<?php echo $latesttvs["id"]; ?>/<?php echo slugify($latesttvs["name"]); ?>">
                                        <div class="see play3"></div>
                                    </a>
                                </div>
                                <div class="data">
                                    <h3><a href="/tv/<?php echo $latesttvs["id"]; ?>/<?php echo slugify($latesttvs["name"]); ?>"><?php echo $latesttvs["name"]; ?></a></h3> <span><?php echo $latesttvs["first_air_date"]; ?></span>
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
                    <li><a href="/tv">TV Shows</a></li>
                    <li><a href="/tv/<?php echo $getid; ?>/<?php echo slugify($currenttv['name']); ?>"><?php echo $currenttv['name']; ?></a></li>
                    <li>Season <?php echo $getseason; ?> Episode <?php echo $getepisode; ?></li>
                </ul>
            </div>
        </div>
<?php include_once 'includes/footer.php'; ?>