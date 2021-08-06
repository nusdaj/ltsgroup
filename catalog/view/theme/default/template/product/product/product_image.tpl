<?php if($images){ ?>
<div class="product-image-column">
  <div class="product-image-main-container related">
      
    <?php if($sticker && $sticker['name']){ ?>
        <?php if($sticker['image']) { ?>
		    <a title="<?= $name; ?>" class="sticker absolute imgsticker">
				<img src="image/<?= $sticker['image']; ?>" alt="<?= $name; ?>" class="img-responsive"/>
			</a>
		<?php } else { ?>
		    <a 
            class="sticker absolute" 
            style="color: <?= $sticker['color']; ?>; background-color: <?= $sticker['background-color']; ?>">
                <?= $sticker['name']; ?>
            </a>
		<?php } ?>      
    <?php } ?>
    
      <?php if($show_special_sticker){ ?>
      <a
      title="<?= $name; ?>" 
      class="special-sticker absolute" 
      style="top:<?= $sticker ? '30px' : '0px' ?>; color: #fff; background-color: red;">
        <?= $text_sale; ?>
      </a>
      <?php } ?>
    <div class="product-image-main">
      <?php foreach($images as $image){ ?>
          <img src="<?= $image['thumb']; ?>" alt="<?= $heading_title; ?>" title="<?= $heading_title; ?>"
            class="main_images pointer" href="<?= $image['popup']; ?>" title="<?= $heading_title; ?>"
          />
      <?php } ?>
    </div>
  </div>
  <div class="product-image-additional-container related">
    <div class="product-image-additional">
      <?php foreach($additional_images as $image){ ?>
      <img src="<?= $image['thumb']; ?>" alt="<?= $heading_title; ?>" title="<?= $heading_title; ?>" class="pointer" />
      <?php } ?>
    </div>
  </div>
  <style type="text/css" >
    .product-image-additional-container .slick-slide {
      margin: 0 5px;
    }
    /* the parent */
    .product-image-additional-container .slick-list {
      margin: 0 -5px;
    }
  </style>
</div>
<?php } ?>