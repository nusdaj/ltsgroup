<div class="featured-module featured_<?= $uqid; ?>">
  <h2 class="text-center target-heading">
    <?= $heading_title; ?>
  </h2>
  <div class="featured section relative" style="opacity: 0;">
    <div id="featured_slider_<?= $uqid; ?>" >
      <?php foreach ($products as $product) { ?>
        <?= html($product); ?>
      <?php } ?>
    </div>
    <script type="text/javascript">

      $(window).load(function(){
        setTimeout(function () {
          featured_product_slick<?= $uqid; ?>();
          AOS.init();
        }, 250);
      });

      function featured_product_slick<?= $uqid; ?>(){
        $("#featured_slider_<?= $uqid; ?>").on('init', function (event, slick) {
          $('#featured_slider_<?= $uqid; ?>').parent().removeAttr('style');
        });

        $("#featured_slider_<?= $uqid; ?>").slick({
          dots: false,
          infinite: false,
          speed: 300,
          slidesToShow: 4,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 1281,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 991,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 860,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
              }
            }
          ],
          prevArrow: "<img src='image/catalog/slicing/homepage/arrow-prev.png' alt='prev' class='slickarr left'/>",
          nextArrow: "<img src='image/catalog/slicing/homepage/arrow-next.png' alt='next' class='slickarr right'/>",
        });

        
      }
    </script>
  </div>
</div>