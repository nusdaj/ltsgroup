<div class="_search">

    <div id="fullpage-search">
        <a id="fullpage-search-close" class="fx-close" onclick="closeSearch()"></a>
        <div class="container">
            <?=$search; ?>
        </div>
    </div>

    <a id="fullpage-search-open" onclick="openSearch()">
        <i class="fa fa-search" ></i>
    </a>

    <script type="text/javascript">
        if($('.search-custom').length){
            $('#fullpage-search').insertBefore('header');
        }

        function closeSearch() {
            $('#fullpage-search').fadeOut(300);
        };
        
        function openSearch() {
            $('#fullpage-search').fadeIn(300);
        };
    </script>

</div>