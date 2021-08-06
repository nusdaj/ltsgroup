<div id="slideshow<?= $module; ?>" class="relative owl-carousel"  style="opacity: 1; width: 100%;">
  <?php foreach ($banners as $banner) { ?>
    <div class="relative h100">
      <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive hidden-xs" />
      <img src="<?= $banner['mobile_image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive visible-xs" />
      <?php if($banner['description']){ ?>
        <div class="slider-slideshow-description w100 absolute position-center-center background-type-<?= $banner['theme']; ?>">
          <div class="container">
            <div class="slider-slideshow-description-texts">
              <?= $banner['description']; ?>

              <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
              <div class="slider-slideshow-description-link">
                <a href="<?= $banner['link']; ?>" class="btn btn-primary">
                  <?= $banner['link_text']; ?>
                </a>
              </div>
              <!--class:slider-slideshow-description-link-->
              <?php } ?>
            </div>
            <!--class:slider-slideshow-description-texts-->
          </div>
          <!--class:container-->
        </div>
        <!--class:slider-slideshow-description-->
      <?php } ?>      
    </div>
  <?php } ?>
</div>
<?php //include('slideshow_script_slick.tpl'); ?>
<?php include('slideshow_script_owl.tpl'); ?>