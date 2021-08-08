<div class="row">
<div class="col-sm-6">
<?php if ($thumb || $images) { ?>
<ul id="sync1" class="thumbnails">
<?php if ($thumb) { ?>
<li class="thumbnail"><img class="lazyOwl" data-src="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></li>
<?php } ?>
<?php if ($images) { ?>
<?php foreach ($images as $image) { ?>
<li class="thumbnail"><img class="lazyOwl" data-src="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></li>
<?php } ?>
<?php } ?>
</ul>
<?php } ?>
</div>
<div class="col-sm-6">
<ul id="sync2" class="thumbnails">
<?php if ($thumb) { ?>
<li class="thumbnail"><img class="lazyOwl" data-src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></li>
<?php } ?>
<?php if ($images) { ?>
<?php foreach ($images as $image) { ?>
<li class="thumbnail"><img class="lazyOwl" data-src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></li>
<?php } ?>
<?php } ?>
</ul>
<div class="clearfix"></div>
<h1><?php echo $heading_title; ?></h1>
<?php if ($price) { ?>
<ul class="list-unstyled">
<?php if (!$special) { ?>
<li>
<h2><?php echo $price; ?></h2>
</li>
<?php } else { ?>
<li><span style="text-decoration:line-through"><?php echo $price; ?></span></li>
<li>
<h2><?php echo $special; ?></h2>
</li>
<?php } ?>
<?php } ?>
<?php if ($tax) { ?>
<li>
<?php echo $text_tax; ?>
<?php echo $tax; ?>
</li>
<?php } ?>
<button type="button"  data-dismiss="modal" class="btn btn-primary btn-lg btn-block" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>')"><i class="fa fa-heart"></i>&nbsp;&nbsp;<?php echo $button_wishlist; ?></button>
<button type="button"  data-dismiss="modal" class="btn btn-primary btn-lg btn-block" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>')"><i class="fa fa-exchange"></i>&nbsp;&nbsp;<?php echo $button_compare; ?></button>
<button type="button"  data-dismiss="modal" class="btn btn-primary btn-lg btn-block" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product_id; ?>')"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<?php echo $button_cart; ?></button>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<?php echo $description; ?>
</div>
</div>
<script type="text/javascript">
    /*<![CDATA[*/
    $(document).ready(function() {
        function c() {
            var d = this.currentItem;
            $("#sync2").find(".owl-item").removeClass("synced").eq(d).addClass("synced"), void 0 !== $("#sync2").data("owlCarousel") && center(d)
        }
        var b = $("#sync1"),
            a = $("#sync2");
        b.owlCarousel({
            singleItem: !0,
            autoPlay: 3e3,
            slideSpeed: 1000,
            navigation: !1,
            navigation: !0,
            navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
            pagination: !1,
            margin:10,
            afterAction: c,
            lazyLoad : true,
            responsiveRefreshRate: 200
        }), a.owlCarousel({
            items: 5,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [979, 4],
            itemsTablet: [768, 4],
            itemsMobile: [479, 3],
            pagination: !1,
            lazyLoad : true,
            responsiveRefreshRate: 100,
            afterInit: function(d) {
                d.find(".owl-item").eq(0).addClass("synced")
            }
        }), $("#sync2").on("click", ".owl-item", function(f) {
            f.preventDefault();
            var d = $(this).data("owlItem");
            b.trigger("owl.goTo", d)
        })
    }); /*]]>*/
</script>