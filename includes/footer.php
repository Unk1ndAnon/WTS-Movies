        <footer class="main tstream-footer">
            <div class="fbox">
                <div class="fcmpbox">
                    <div class="copy">© <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</div>
                    <span class="top-page"><a href="#" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;"><i class="fa fa-angle-up" aria-hidden="true"></i></a></span>
                    <div class="fmenu"><ul class="menu">
                        <li><a href="/">Home</a></li><li><a href="/dmca">DMCA</a></li>
                        <li><a href="/privacy">Privacy Policy</a></li><li><a href="/terms">Terms and Conditions</a></li>
                    </ul></div>
                    <div class="tmdb-attribution"><a href="https://www.themoviedb.org/" target="_blank" rel="noopener noreferrer"><img src="/img/dblogo.png" alt="The Movie Database" width="80" height="32" loading="lazy"></a><span>This product uses the TMDB API but is not endorsed or certified by TMDB.</span></div>
                </div>
            </div>
        </footer>
    </div>
    <?php foreach (['analytics.php','direct-link-ad-code.php','pop-ad-code.php'] as $optionalInclude): $optionalPath=__DIR__.'/'.$optionalInclude; if (is_file($optionalPath)) include $optionalPath; endforeach; ?>
    <script src="/JS/main.js?v=2.1" defer></script>
</body>
</html>
