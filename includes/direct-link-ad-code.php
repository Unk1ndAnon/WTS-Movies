<?php if(!empty($directlink)){ ?>
<script type="text/javascript">
    $(document).ready(function() {
        var ec = "<?php echo $directlink; ?>";
        $("head").append('<link rel="preconnect dns-prefetch" href="' + ec + '">');
        $("body").one("click", function() {
            window.open(ec, "_blank", "");
        });
    });
</script>
<?php } ?>