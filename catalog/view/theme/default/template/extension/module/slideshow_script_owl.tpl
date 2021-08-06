<script type="text/javascript">
    // Note.. it supports Animate.css
    // Manual Slider don't support animate css
    $('#slideshow<?= $module; ?>').owlCarousel({
        items: 1,
        <?php if (count($banners) > 1) { ?>
            loop: true,
        <?php } ?>

        autoplay: true,
        autoplayTimeout: 5000,
        
        smartSpeed: 450,
        
        nav: <?= $arrows; ?>,
        navText: ['<div class="pointer absolute position-top-left h100 slider-nav slider-nav-left hover-show"><div class="absolute position-center-center"><i class="fa fa-chevron-left fa-2em"></i></div></div>', '<div class="pointer absolute position-top-right h100 slider-nav slider-nav-right hover-show"><div class="absolute position-center-center"><i class="fa fa-chevron-right fa-2em"></i></div></div>'],
        
        dots: <?= $dots; ?>,
        dotsClass: 'slider-dots slider-custom-dots absolute position-bottom-left w100 list-inline text-center',
        
        //animateOut: 'slideOutDown',
        //animateIn: 'slideInDown',
    });
</script>