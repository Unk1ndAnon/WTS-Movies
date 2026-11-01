# TStream WATCH — WTS-Movies upgraded

This branch adapts the original GPL-3.0 WTS-Movies PHP project for https://tstream.watch.

## Requirements
- PHP 8.1+ (PHP 8.2+ recommended)
- Apache with mod_rewrite and AllowOverride All
- PHP extensions: cURL (recommended), JSON, OpenSSL
- HTTPS enabled for the site
- A TMDB API v3 key

## Installation
1. Upload the contents of this repository to the domain document root.
2. Copy includes/config.local.example.php to includes/config.local.php.
3. Edit includes/config.local.php and set your TMDB API key. Do not commit this private file.
4. Ensure PHP can create/write a private tstream-watch-cache directory one level above the web root (the application creates it automatically; ensure the PHP user can write there).
5. Enable Apache mod_rewrite, make sure the document root allows .htaccess overrides, and keep the included .htaccess.
6. Point DNS for tstream.watch to your host and configure a valid SSL certificate.
7. Verify the homepage, movie URL, TV show URL, episode URL and XML sitemap.

## TMDB configuration
The API key is read from includes/config.local.php or the TMDB_API_KEY environment variable. It is not printed into frontend HTML by the updated helper. TMDB responses are cached in a private directory outside the public web root to reduce requests. TLS certificate verification is enabled.

The site uses TMDB metadata and artwork. This product uses the TMDB API but is not endorsed or certified by TMDB.

## Video embeds
Two iframe providers are integrated:
- MovieTV API: https://movietvapi.stream/embed/movie/{id} and https://movietvapi.stream/embed/tv/{id}/{season}/{episode}
- FMOV: https://fmov.my/embed/movie/{id} and https://fmov.my/embed/series/{id}/{season}/{episode}

MovieTV is the initial selection. Visitors can switch to FMOV from the player controls. MovieTV has its own internal provider failover. Cross-origin iframe errors are not exposed to the parent page, so the website does not pretend it can automatically detect and switch between the two separate iframe hosts.

Both embed services are third-party services and can change availability or parameters independently of this repository.

## SEO and routes
Clean public routes are mapped by .htaccess. Movie and TV episode pages render server-side metadata, canonical URLs, Open Graph/Twitter tags and relevant JSON-LD. Search pages are noindex,follow. The XML sitemap index includes static pages and paginated TMDB movie/TV sitemap endpoints.

## Security notes
- Legacy encrypted/eval player payload removed.
- TMDB requests require valid TLS certificate verification and use bounded timeouts.
- Search text and API-sourced page text are escaped in the updated views.
- Private TMDB config and runtime cache are excluded from Git.

Review third-party provider terms, rights to display/embed content, DMCA procedures and local law before launching the service.
