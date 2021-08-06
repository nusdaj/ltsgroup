<?= $header; ?>
<div class="container">
  <?= $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?= $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?= $class; ?>">
      <h2><?= $heading_title; ?></h2>

      <div class="search-container">
        <div class="search-description"><?= $text_description; ?></div>
        <div class="search-options">

          <select name="category_id" class="form-control">
            <option value="0"><?= $text_category; ?></option>
            <?php foreach ($categories as $category_1) { ?>
            <?php if ($category_1['category_id'] == $category_id) { ?>
            <option value="<?= $category_1['category_id']; ?>" selected="selected"><?= $category_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?= $category_1['category_id']; ?>"><?= $category_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <?php if ($category_2['category_id'] == $category_id) { ?>
            <option value="<?= $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $category_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?= $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $category_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <?php if ($category_3['category_id'] == $category_id) { ?>
            <option value="<?= $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $category_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?= $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $category_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>

          <div class="input-group">
            <input type="text" name="search" value="<?= $search; ?>" placeholder="<?= $text_keyword; ?>" id="input-search" class="form-control" />
            <div class="input-group-btn" >
              <button type="button" id="button-search" class="btn btn-default" >
                <i class="fa fa-search" ></i>
              </button>
            </div>
          </div>

        </div>
      </div>

      <?php if ($products) { ?>
        <div class="product-view">
          <?php foreach ($products as $product) { ?>
            <?= $product; ?>
          <?php } ?>
        </div>

      <div class="row">
        <div class="col-sm-12 text-center"><?= $pagination; ?></div>
      </div>

      <?php } else { ?>
        <p><?= $text_empty; ?></p>
      <?php } ?>

      </div>

      <?php /* AJ Apr 12, begin: add Modal window. Copy from Category.tpl; Apr 14, begin: change the error from hint & let validation done at browser */ ?>    
      <!-- Modal AJ Apr 15: copied from home.tpl -->
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
                    <input type="text" name="name" value="<?= $name; ?>" id="input-name" class="form-control" placeholder="<?= $entry_name; ?>" minlength="3" maxlength="32" /> 
                    <?php if ($hint_name) { ?>
                    <div class="text-info"><?= $hint_name; ?></div>
                    <?php } ?>                
                  </div>
                  <div class="form-group required">
                    <input type="email" name="email" value="<?= $email; ?>" id="input-email" class="form-control" placeholder="<?= $entry_email; ?>" />
                    <?php if ($hint_email) { ?>
                    <div class="text-info"><?= $hint_email; ?></div>
                    <?php } ?>
                  </div>
                  <div class="form-group">
                    <input type="tel" name="telephone" value="<?= $telephone; ?>" id="input-telephone" class="form-control input-number" placeholder="<?= $entry_telephone; ?>" />
                    <?php if ($hint_telephone) { ?>
                    <div class="text-info"><?= $hint_telephone; ?></div>
                    <?php } ?>
                  </div>
                  <div class="form-group hidden">
                    <input type="text" name="subject" id="input-subject" class="form-control" value="Enquiry Now from Searched Results." />
                  </div>
                  <div class="form-group">
                    <input type="text" name="featuredProduct" id="input-products" class="form-control" readonly />
                  </div>
                  <div class="form-group required">
                    <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control" placeholder="<?= $entry_enquiry; ?>" minlength="10" maxlength="300"><?= $enquiry; ?></textarea>
                    <?php if ($hint_enquiry) { ?>
                    <div class="text-info"><?= $hint_enquiry; ?></div>
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
      <!-- AJ Apr 12, end: add Modal window; Apr 14, end: hint & validation at browser; Apr 15: copied from home.tpl -->    



    <?= $column_right; ?></div>
    <?= $content_bottom; ?>
</div>
<script type="text/javascript"><!--
$('#button-search').bind('click', function() {
	url = 'index.php?route=product/search';

	var search = $('#content input[name=\'search\']').prop('value');

	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var category_id = $('#content select[name=\'category_id\']').prop('value');

	if (category_id > 0) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}

	var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');

	if (sub_category) {
		url += '&sub_category=true';
	}

	var filter_description = $('#content input[name=\'description\']:checked').prop('value');

	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

$('#content input[name=\'search\']').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'category_id\']').on('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').prop('disabled', true);
	} else {
		$('input[name=\'sub_category\']').prop('disabled', false);
	}
});

$('select[name=\'category_id\']').trigger('change');
--></script>
<script>
  fbq('track', 'Search');
</script>
<?= $footer; ?>


<?php /* AJ Apr 12, begin: add call to the Modal window; Apr 15: copied from home.tpl */ ?>    
<script type="text/javascript">
function toggleProductModal(product) {
  $("#enquiryModal #input-products").val(product);
}
</script>
<?php /* AJ Apr 12, end: add call to the Modal window; Apr 15: copied from home.tpl */ ?>