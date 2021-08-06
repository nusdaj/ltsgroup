<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="review-container">
  <p class="review-author"><?= $review['author']; ?></p>
  <div class="rating">
    <?php $count=0; for ($i = 1; $i <= 5; $i++) { ?>
      <?php if ($review['rating'] < $i) { ?>
      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
      <?php } else { ?>
      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
      <?php $count++;} ?>
      <?php } ?>
      <span>(<?= $count; ?>)</span>
  </div>
  <p><?php echo $review['text']; ?></p>
</div>


<?php } ?>
<div class="text-center"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
