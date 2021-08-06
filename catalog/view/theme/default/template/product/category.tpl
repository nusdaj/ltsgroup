<?php echo $header; ?>
<div class="bgproduct">
<div class="container">
  <?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">

    <h2><?php echo $heading_title; ?></h2> 
  
    <?php echo $column_left; ?>

    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

    <div id="content" class="<?php echo $class; ?>">

      <div id="product-filter-replace">
        <div id="product-filter-loading-overlay"></div>
        
          <?php if ($products) { ?>
          
            <?php include_once('sort_order.tpl'); ?>
              
            <div id="product-filter-detect">
              
              <div class="row row-special">
                <div class="product-view">
                  <?php foreach ($products as $product) { ?>
                    <?php echo $product; ?>
                  <?php } ?>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12 text-center"><?php echo $pagination; ?></div>
              </div>

            </div> <!-- product-filter-detect -->

          <?php } ?>

          <?php if (!$products) { ?>
          
            <p><?php echo $text_empty; ?></p>
            <div class="buttons hidden">
              <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
            </div>

          <?php } ?>

      </div> <!-- product-filter-replace -->

    <!-- Modal AJ Apr 15: modified following home.tpl -->
    <div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <p class="productmodal-title" id="exampleModalLabel">Enquire Now</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <div class="modal-body">      
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="contact-body">
            <div class="form-group required">
              <input type="text" name="name" value="<?= $name; ?>" id="input-name" class="form-control" placeholder="<?= $entry_name; ?>" minlength="3" maxlength="32" /> <!-- AJ Apr 12; chagned to follow the convention -->
              <?php if ($error_name) { ?>
                <div class="text-danger"><?= $error_name; ?></div>
              <?php } ?>                
            </div>
          
            <div class="form-group required">
              <input type="email" name="email" value="<?= $email; ?>" id="input-email" class="form-control" placeholder="<?= $entry_email; ?>" />
              <?php if ($error_email) { ?>
                <div class="text-danger"><?= $error_email; ?></div>
              <?php } ?>
            </div>

            <div class="form-group">
              <input type="tel" name="telephone" value="<?= $telephone; ?>" id="input-telephone" class="form-control input-number" placeholder="<?= $entry_telephone; ?>" />
              <?php if ($error_telephone) { ?>
                <div class="text-danger"><?= $error_telephone; ?></div>
              <?php } ?>
            </div>

            <div class="form-group hidden">
               <input type="text" name="subject" id="input-subject" class="form-control" value="Enquire Now from Product List in categories" />
            </div> 
            
            <div class="form-group">
               <input type="text" name="featuredProduct" id="input-products" class="form-control" value="<?php $featuredProduct; ?>" readonly />
            </div> 

            <div class="form-group required">
              <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control" placeholder="<?= $entry_enquiry; ?>"  minlength="10" maxlength="300"><?= $enquiry; ?></textarea>
              <?php if ($error_enquiry) { ?>
                <div class="text-danger"><?= $error_enquiry; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="modal-footer">          
            <div class="contact-footer text-center">
              <?= $captcha; ?>
              <input class="btn btn-primary pull-sm-right btn-submit" type="submit" value="Submit" />
            </div>
          </div>
        </form>
        </div>
      </div>
      </div>
    </div>
    </div> <!-- #content -->

    <?php echo $column_right; ?></div>
    <?php echo $content_bottom; ?>
</div>
</div>

<?php /* AJ Apr 21, begin: check if validation failed. failed, show the modal dialog */ ?>
<?php if ($validation_failed == true) {
  echo  "<script>  $(function() { $('#input-products').val('$featuredProduct'); $('#enquiryModal').modal('show');  }); </script>";
} ?>

<?php echo $footer; ?>

<script type="text/javascript">
function toggleProductModal(product) {
  $("#enquiryModal #input-products").val(product);
}
</script>