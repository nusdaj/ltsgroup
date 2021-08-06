<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php include_once('product_sticker.tpl'); ?>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="deleteItems();"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <?php if ($error_facebook_sync) {?>
    <div class="alert alert-danger hidden"><i class="fa fa-exclamation-circle"></i> <?= $error_facebook_sync; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_facebook_delete) {?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_facebook_delete; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-image"><?php echo $entry_image; ?></label>
                <select name="filter_image" id="input-image" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_image) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_image && !is_null($filter_image)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">

              <div class="form-group">
                <label class="control-label" for="input-out_of_stock"><?php echo $entry_out_of_stock; ?></label>
                <select name="filter_out_of_stock" id="input-out_of_stock" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_out_of_stock == '0') {  ?>
                  <option value="0" selected="selected"><?php echo $text_out_stock; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_out_stock; ?></option>
                  <?php } ?>
                  <?php if ($filter_out_of_stock == 1) { ?>
                  <option value="1" selected="selected"><?php echo $text_low_stock; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_low_stock; ?></option>
                  <?php } ?>
                  <?php if ($filter_out_of_stock == 2) { ?>
                  <option value="2" selected="selected"><?php echo $text_in_stock; ?></option>
                  <?php } else { ?>
                  <option value="2"><?php echo $text_in_stock; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label"><?= $entry_category ?></label>
                <select name="category_id" class="form-control">
                  <option value="0"><?= $text_select ?></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id'] == $category_id) { ?>
                  <option value="<?= $category['category_id']; ?>" selected="selected"><?= $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?= $category['category_id']; ?>"><?= $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                  <label class="control-label"><?= $entry_manufacturer ?></label>
                  <select name="manufacturer_id" class="form-control">
                    <option value="0"><?= $text_select ?></option>
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                    <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                    <option value="<?= $manufacturer['manufacturer_id']; ?>" selected="selected"><?= $manufacturer['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?= $manufacturer['manufacturer_id']; ?>"><?= $manufacturer['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-sm-12">
                <button type="button" id="button-clear" class="btn btn-default pull-right"><i class="fa fa-refresh"></i> <?php echo $button_clear; ?></button>
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
              </div>
            
          </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?= $entry_category ?></td>
                  <td class="text-left"><?= $entry_manufacturer ?></td>
                  <td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['categories']; ?></td>
                  <td class="text-left"><?php echo $product['manufacturer']; ?></td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-right"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= $low_stock) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                  <td class="text-left">
                    <label class="switch">
                      <input type="checkbox" value="<?=$product['product_id']?>" <?= $product['status'] == 'Enabled'?'checked':''; ?> class="toggle-status">
                      <span class="slider round"></span>
                    </label>      
                  </td>
                  <td class="text-right">
                    <?php if($product['href']){ ?>
                      <a data-href = "<?php echo $product['href']; ?>" onclick = 'copythis(this);' data-toggle="tooltip" title="Click to copy Url" class="btn btn-success">
                      <i class="fa fa-copy"></i>&nbsp;&nbsp;Url</a>
                    <?php } ?>
                  
                  <a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
    function deleteItems() {
      if(confirm('<?php echo $text_confirm; ?>')) {
        $('form').attr('action', '<?php echo html($delete); ?>');
        $('form').submit();
      }else {
        return false;
      }
    }

    $('.toggle-status').on('click', function() {
      $.ajax({url: 'index.php?route=catalog/product/changeStatus&token=<?php echo $token; ?>&product_id=' + $(this).val()});
    });

    $('#button-clear').on('click', function() {
      var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';
      window.location= url;
    });
    $('#button-filter').on('click', function() {
    	var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

    	var filter_name = $('input[name=\'filter_name\']').val();

    	if (filter_name) {
    		url += '&filter_name=' + encodeURIComponent(filter_name);
    	}

    	var filter_model = $('input[name=\'filter_model\']').val();

    	if (filter_model) {
    		url += '&filter_model=' + encodeURIComponent(filter_model);
    	}

    	var filter_price = $('input[name=\'filter_price\']').val();

    	if (filter_price) {
    		url += '&filter_price=' + encodeURIComponent(filter_price);
    	}

    	var filter_quantity = $('input[name=\'filter_quantity\']').val();

    	if (filter_quantity) {
    		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    	}

    	var filter_status = $('select[name=\'filter_status\']').val();

    	if (filter_status != '*') {
    		url += '&filter_status=' + encodeURIComponent(filter_status);
    	}

      var filter_image = $('select[name=\'filter_image\']').val();

      if (filter_image != '*') {
        url += '&filter_image=' + encodeURIComponent(filter_image);
      }

      var filter_out_of_stock = $('select[name=\'filter_out_of_stock\']').val();

      if (filter_out_of_stock) {
        url += '&filter_out_of_stock=' + encodeURIComponent(filter_out_of_stock);
      }
      console.log(filter_out_of_stock);
      
      var category_id = $('select[name=\'category_id\']').val();

      if (category_id) {
        url += '&category_id=' + encodeURIComponent(category_id);
      }

      var manufacturer_id = $('select[name=\'manufacturer_id\']').val();

      if (manufacturer_id) {
        url += '&manufacturer_id=' + encodeURIComponent(manufacturer_id);
      }

    	location = url;
    });
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>