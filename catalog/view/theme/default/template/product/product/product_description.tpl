<p><?= $heading_title; ?></p>
<h3 class="product-nameinner"><?= $product_name; ?></h3>
<?php if ($price && !$enquiry) { ?>
<ul class="list-unstyled prices">
    
  <!-- AJ: begin, added Mar 7: display price range; Apr 7: modified, add in title --> 
  <?php if ($price_lowest) { ?>
  <li>
    <div style="font-size:80%; font-weight:300; color:GoldenRod">Range: <?= $price_lowest; ?> - <?= $price_highest; ?></div>
  </li>
  <?php } ?>
  <!--AJ: end-->     
    
  <?php if (!$special) { ?>
  <li>
<!-- AJ Apr 7: remarked    <div class="product-price orig-prices old-prices" ><?= $price; ?></div>   -->
<!-- AJ Apr 7: added begin; display both UNIT price and TOTAL price -->
<span style="font-size: 80%; font-weight:300; color:DarkGray;">Unit: </span>
<span class="product-price orig-prices old-prices" ><?= $price; ?></span>
<span style="font-size: 80%; font-weight:300; color:DarkGray;">Total: </span>
<span class="product-price orig-prices total-prices" ></span>
<!-- AJ Apr 7: added end; display both UNIT price and TOTAL price -->
  </li>
  <?php } else { ?>
  <li>
<!-- AJ Apr 7: remarked    
    <span class="product-special-price new-prices"><?= $special; ?></span>
    <span style="text-decoration: line-through;" class="old-prices"><?= $price; ?></span>
-->
<!-- AJ Apr 7: added begin; display both UNIT and TOTAL prices -->    
    <span style="font-size: 80%; font-weight:300; color:DarkGray;">Unit: </span>
    <span class="product-special-price new-prices"><?= $special; ?></span>
    <span style="text-decoration: line-through;" class="old-prices"><?= $price; ?></span>
    <span style="font-size: 80%; font-weight:300; color:DarkGray;">Total: </span>
    <span class="product-price orig-prices total-prices" ></span>
<!-- AJ Apr 7: added end; display both UNIT and TOTAL prices -->    
  </li>
  <?php } ?>
  <?php if ($tax) { ?>
  <li class="product-tax-price product-tax" ><?= $text_tax; ?> <?= $tax; ?></li>
  <?php } ?>
  <?php if ($points) { ?>
  <li><?= $text_points; ?> <?= $points; ?></li>
  <?php } ?>


</ul>
<?php } ?>
<?php if($enquiry){ ?>
<div class="enquiry-block">
  <div class="label label-primary">
    <?= $text_enquiry_item; ?>
  </div>
</div>
<?php } ?>
<div class="reviewshare">
  <?php if ($review_status) { ?>
  <div class="rating">
      <?php $count=0; for ($i = 1; $i <= 5; $i++) { ?>
      <?php if ($rating < $i) { ?>
      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
      <?php } else { ?>
      <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
      <?php $count++; } ?>
      <?php } ?>
    <span>(<?= $count; ?>)</span>
  </div>
  <?php } ?>
  <?php if($share_html){ ?>
    <div class="flex">
       <div class="prodshare">
        <a href="http://www.facebook.com/sharer.php?u=<?= $share; ?>" target="_blank">
          <img src="image/catalog/slicing/happening/icon_happening-sm-fb.png" alt="Facebook" class="img-responsive pd-r10" />
        </a>
      </div>
      <div class="prodshare">
        <a href="https://twitter.com/share?url=<?= $share; ?>" target="_blank">
          <img src="image/catalog/slicing/happening/icon_happening-sm-twitter.png" alt="Twitter" class="img-responsive pd-r10" />
        </a>
      </div>
      <div class="prodshare">
        <a href="mailto:?Subject=<?= $product_name; ?> <?= $share; ?>">
            <img src="image/catalog/slicing/email.png" alt="Email" />
        </a>
      </div>
    </div>
  <?php } ?>
</div>

<div class="product-description">
  <?= $description; ?>
</div>

<?php include_once('product_options.tpl'); ?>

<?= $waiting_module; ?>