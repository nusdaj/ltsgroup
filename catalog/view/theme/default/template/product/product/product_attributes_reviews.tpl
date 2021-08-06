<?php if ($attribute_groups || $review_status || isset($available_offers['tab']) && $available_offers['tab'] ) { ?>
<ul id="product-tabs" class="nav nav-tabs">
  <?php if(isset($available_offers['tab']) && $available_offers['tab']) { ?>
  <li><a href="#tab-available-offers" data-toggle="tab"><?php echo $tab_available_offers; ?></a></li>
  <?php } ?>
<?php if ($attribute_groups) { ?>
  <?php foreach ($attribute_groups as $index_1 => $attribute_group) { ?>
    <?php foreach ($attribute_group['attribute'] as $index_2 => $attribute) { ?>
      <li class="<?= !$index_2?'active':''; ?>" >
        <a href="#tab-attribute-<?= $index_1; ?>-<?= $index_2; ?>" data-toggle="tab"><?= $attribute['name']; ?></a>
      </li>
    <?php } ?>
  <?php } ?>
<?php } ?>
<?php if ($review_status) { ?>
  <li><a href="#tab-review" data-toggle="tab"><?= $tab_review; ?></a></li>
<?php } ?>
</ul>

<div class="tab-content">

<?php if(isset($available_offers['tab']) && $available_offers['tab']) { ?>
<div class="tab-pane" id="tab-available-offers"><?php echo $available_offers['tab']; ?></div>
<?php } ?>
<?php if ($attribute_groups) { ?>
  <?php foreach ($attribute_groups as $index_1 => $attribute_group) { ?>
    <?php foreach ($attribute_group['attribute'] as $index_2 => $attribute) { ?>
      <div class="tab-pane <?= !$index_2?'active':''; ?>" id="tab-attribute-<?= $index_1; ?>-<?= $index_2; ?>">
        <?= html($attribute['text']); ?>
      </div>
    <?php } ?> 
  <?php } ?>
<?php } ?>

<?php if ($review_status) { ?>
<div class="tab-pane" id="tab-review">
  <form class="form-horizontal" id="form-review">
    <div id="review"></div>
    <h3><?= $text_write; ?></h3>
    <?php if ($review_guest) { ?>
      <?php if ( $purchased ) { ?>
        <div class="review-form">
          <div class="form-group required">
            <input type="text" name="name" value="<?= $customer_name; ?>" id="input-name" class="form-control" placeholder="Name" />
          </div>
          <div class="form-group required">
            <input type="email" name="email" id="input-email" class="form-control" placeholder="Email" />
          </div>
          <div class="form-group required">
            <textarea name="text" rows="5" id="input-review" class="form-control" placeholder="<?= $entry_review; ?>"></textarea>
          </div>
        </div>
        <div class="form-group required">
          <div class="col-sm-12">
            <label class="control-label"><?= $entry_rating; ?></label>
            &nbsp;&nbsp;&nbsp; <?= $entry_bad; ?>&nbsp;
            <input type="radio" name="rating" value="1" />
            &nbsp;
            <input type="radio" name="rating" value="2" />
            &nbsp;
            <input type="radio" name="rating" value="3" />
            &nbsp;
            <input type="radio" name="rating" value="4" />
            &nbsp;
            <input type="radio" name="rating" value="5" />
            &nbsp;<?= $entry_good; ?></div>
        </div>
        <?php // below "col-sm-12" is fix for offset negative margin left right in the recaptcha markup ?>
        <div class="buttons clearfix">
            <?= $captcha; ?>
            <button type="button" id="button-review" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary pull-sm-right">Post Review</button>
        </div>
      <?php } else { ?>
        <?= $text_purchase ?>
      <?php } ?>
    <?php } else { ?>
        <?= $text_login; ?>
    <?php } ?>
  </form>
</div>
<?php } ?>
</div>
<?php } ?>