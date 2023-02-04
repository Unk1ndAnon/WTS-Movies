<?php if(!isset($metaimg)){$metaimg = 'https://'.$Domainname.'/img/home.PNG';}?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <title><?php echo $metatitle; ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="msapplication-config" content="/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo $metadesc; ?>">
    <meta name="image" content="<?php echo $metaimg; ?>">
    <meta itemprop="name" content="<?php echo $metatitle; ?>">
    <meta itemprop="description" content="<?php echo $metadesc; ?>">
    <meta itemprop="image" content="<?php echo $metaimg; ?>">
    <meta property="og:title" content="<?php echo $metatitle; ?>">
    <meta property="og:description" content="<?php echo $metadesc; ?>">
    <meta property="og:image" content="<?php echo $metaimg; ?>">
    <meta property="og:url" content="https://<?php echo $Domainname; ?>/">
    <meta property="og:site_name" content="<?php echo $metatitle; ?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="<?php echo $metatitle; ?>">
    <meta property="twitter:description" content="<?php echo $metadesc; ?>">
    <meta property="twitter:image:src" content="<?php echo $metaimg; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon/favicon.ico">
    <link rel="canonical" href="https://<?php echo $Domainname; ?>/<?php if(isset($canonical)){echo $canonical;} ?> ">
    <link rel='stylesheet' href='/CSS/style.css'>
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
    <?php if(isset($metaschema)){echo $metaschema;} ?> 
</head>

<body>
    <div id="data-container">
        <header class="responsive">
            <div id="menubtn" class="nav"><a class="aresp nav-resp"><i class="fa fa-bars"></i></a></div>
            <div id="searchbtn" class="search"><a class="aresp search-resp"><i class="fa fa-search"></i></a></div>
            <div class="logo">
                <a href="/"><img src='/img/logo.png' alt='<?php echo $SiteTitle; ?> logo' /></a>
            </div>
        </header>
        <div class="search_responsive">
            <form onsubmit="" method="get" id="form-search-resp" class="form-resp-ab" action="">
                <input type="text" class="footer_search_input" placeholder="Search..." name="keyword_search" id="keyword" value="" autocomplete="off" onclick="">
                <input id="key_pres" name="key_pres" value="" type="hidden" />
                <input id="keyword_search_replace" name="keyword_search_replace" value="" type="hidden" />
            </form>
            <div class="live-search" id="header_search_autocomplete"></div>
        </div>
        <div id="arch-menu" class="menuresp">
            <div class="menu">
                <ul class="resp">
                    <li><a href="/"><i class="fas fa-home"></i><div class="mvs">Home</div></a></li>
                    <li><a href="/movies"><i class="fas fa-film"></i><div class="mvs">Movies</div></a></li>
                    <li><a href="/trending-movies"><i class="fas fa-chart-line"></i><div class="mvs">Trending Movies</div></a></li>
                    <li><a href="/top-rated-movies"><i class="fas fa-star"></i><div class="mvs">Top Rated Movies</div></a></li>
                    <li><a href="/tv"><i class="fas fa-tv"></i><div class="mvs">TV Shows</div></a></li>
                    <li><a href="/trending-tv"><i class="fas fa-chart-line"></i></i><div class="mvs">Trending TV Shows</div></a></li>
                    <li><a href="/top-rated-tv"><i class="fas fa-star"></i></i><div class="mvs">Top Rated TV Shows</div></a></li>
                    <li><a href="https://autoembed.to/#embed-movies"><i class="fas fa-link"></i></i><div class="mvs">Auto Embed Movies</div></a></li>
                    <li><a href="https://autoembed.to/#embed-tv-shows"><i class="fas fa-link"></i></i><div class="mvs">Auto Embed TV Shows</div></a></li>
                </ul>
            </div>
        </div> 