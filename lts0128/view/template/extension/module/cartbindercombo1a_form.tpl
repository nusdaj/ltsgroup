<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
   <div class="container-fluid">
          <div class="pull-right touragain">
            <a data-toggle="tooltip" title="Display Promotions Using Add Offers Page" class="btn btn-primary addoffers" target="_blank" href="<?php echo $addoffers; ?>"><i class="fa fa-star"></i> <?php echo $text_addoffers; ?></a>
            <a data-toggle="tooltip" title="Learn how to use this combination" class="btn btn-primary documentation" target="_blank" href="http://blog.cartbinder.com/documentation/buy-x-products-and-get-y-offer-on-same-products/"><i class="fa fa-book"></i> Documentation</a>
         </div>
    </div>
    </div>
  <div class="page-header">
    <div class="container-fluid">
          <h2><?php echo $heading_title; ?></h2>
          <br>
           <medium><i class="headerinfo3"><?php echo $headerinfo3; ?></i></medium>
    </div>
  </div>
  <div class="page-header">
    <div class="container-fluid">
          <div class="pull-right">
        <a onclick="$('#form').submit();"  class="btn btn-success"><i class="fa fa-save"></i> save</a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger"><i class="fa fa-reply"></i> cancel</a>
        </div>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
   <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title offerform"><i class="fa fa-list"></i> <?php echo $headerinfo1; ?></h3>
      </div>
     <div class="panel-body">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
       <div class="form-group">
         <label class="col-sm-12 control-label" for="input-sales" style="text-align:left;"><?php echo $text_sales; ?></label>
        </div>     
        <div class="form-group">
         <label for="status" class="col-sm-2 control-label status" style="text-align:left;"><?php echo $text_status; ?></label>
         <div class="col-sm-2">
          <select id="status" name="status" class="form-control">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
          <label for="name"  class="col-sm-3 control-label"><?php echo $text_nameform; ?></label>
          <div class="col-sm-3">
          <input type="text" id="name" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $text_offername; ?>" class="form-control name" />
          <?php if($error_name) { ?> 
             <div class="text-danger"><?php echo $error_name; ?></div>
          <?php } ?>
          </div>
        </div>   
        <div class="form-group">
         <label class="col-sm-5 control-label" for="input-new" style="text-align:left;"><?php echo $text_new; ?></label>
          <div class="col-sm-2">
         <input type="text" id="primaryquant" name="primaryquant" value="<?php echo $primaryquant; ?>" class="form-control" />
         </div> 
         <label class="col-sm-4  control-label" for="input-quantity" style="text-align:left;"><?php echo $text_quantitybelow; ?></label>
        </div>       
        <div class="form-group primartyproducts">
          <div class="col-sm-12">
            <input type="text" name="productname" value="" placeholder="<?php echo $text_primaryproducts; ?>" id="input-product" class="form-control primaryproduct" />
            <div id="product" class="well well-sm" style="height: 150px; overflow: auto;">
              <?php foreach ($primaryproducts as $product) { ?>
              <div id="product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                <input type="hidden" name="primaryproducts[]" value="<?php echo $product['product_id']; ?>" />
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="form-group">
         <label class="col-sm-3 control-label" for="input-free" style="text-align:left;"><?php echo $text_free; ?></label>
         <div class="col-sm-2">
         <input type="text" id="secondaryquant" name="secondaryquant" value="<?php echo $secondaryquant; ?>" class="form-control" />
         </div> 
         <label class="col-sm-4  control-label" for="input-quantity" style="text-align:left;"><?php echo $text_quantitybelow2; ?></label>
          </div>
        <div class="form-group">
          <label class="col-sm-1 control-label" for="input-at" style="text-align:left;"><?php echo $text_at; ?></label>
         <div class="col-sm-2">
          <select id="type" name="type" class="form-control type">
              <?php if ($type) { ?>
              <option value="1" selected="selected"><?php echo $text_fixed; ?></option>
              <option value="0"><?php echo $text_percentage; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_fixed; ?></option>
              <option value="0" selected="selected"><?php echo $text_percentage; ?></option>
              <?php } ?>
            </select>
          </div>
           <label class="col-sm-2 control-label" for="input-discount" style="text-align:left;"><?php echo $text_discount; ?></label>
           <div class="col-sm-1">
           <input type="text" id="discount" name="discount" value="<?php echo $discount; ?>" class="form-control discount" />
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-2 control-label" for="input-multidiscount" style="text-align:left;"><h3>or multi discount</h3></label>
         <div class="col-sm-10">
           <input type="text" id="multidiscount" name="multidiscount" value="<?php echo $multidiscount; ?>" class="form-control multidiscount"  placeholder="Format: Qty:Discount;Qty:Discount  EX: 2:10p;3:15p If you write p it means percentage" />
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-12 control-label" for="input-conditions" style="text-align:left;"><?php echo $text_conditions; ?></label>
        </div>
        <div class="form-group customergroup">
          <label class="col-sm-5 control-label" for="input-at" style="text-align:left;"><?php echo $text_customergroup; ?></label>
          <div class="col-sm-7">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
              <?php foreach ($customergroups as $customergroup) { ?>
              <div class="checkbox">
                <label>
                  <?php if (!empty($cids) && in_array($customergroup['customer_group_id'], $cids)) { ?>
                  <input type="checkbox" name="cids[]" value="<?php echo $customergroup['customer_group_id']; ?>" checked="checked" />
                  <?php echo $customergroup['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="cids[]" value="<?php echo $customergroup['customer_group_id']; ?>" />
                  <?php echo $customergroup['name']; ?>
                  <?php } ?>
                </label>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="form-group daterange">
          <label class="col-sm-5 control-label" for="input-at" style="text-align:left;"><?php echo $text_daterange; ?></label>
          <div class="col-sm-3">
              <div class="input-group date">
                <input type="text" name="datestart" value="<?php echo $datestart; ?>" placeholder="<?php echo $text_datestart; ?>" data-date-format="YYYY-MM-DD" id="input-datestart" class="form-control" />
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
                <div class="input-group date">
                <input type="text" name="dateend" value="<?php echo $dateend; ?>" placeholder="<?php echo $text_dateend; ?>" data-date-format="YYYY-MM-DD" id="input-dateend" class="form-control" />
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
        </div>
        <div class="form-group">
          <label class="col-sm-5 control-label" for="input-autoadd" style="text-align:left;"><?php echo $text_autoadd; ?></label>
         <div class="col-sm-2">
          <select id="input-autoadd" name="autoadd" class="form-control type">
            <?php if ($autoadd) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
         <div class="form-group">
          <label class="col-sm-5 control-label" for="input-at" style="text-align:left;"><?php echo $text_salesoffer; ?></label>
         <div class="col-sm-2">
          <select id="sales_offer_id" name="sales_offer_id" class="form-control type">
            <option value="">Please Select</option>
             <?php foreach ($offerpages as $key => $value) { ?>
              <?php if ($value['salescombopge_id'] == $sales_offer_id) { ?>
              <option value="<?php echo $value['salescombopge_id']; ?>" selected="selected"><?php echo $value['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $value['salescombopge_id']; ?>"><?php echo $value['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-5 control-label" for="input-showoffer" style="text-align:left;"><?php echo $text_showoffer; ?></label>
         <div class="col-sm-7">
          <select id="input-showoffer" name="showoffer" class="form-control">
              <?php if ($showoffer) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
            <label class="col-sm-12 control-label" for="input-displaylocation" style="text-align:left;"><?php echo $text_displaylocation; ?></label>
            <div class="col-sm-12">
                  <label class="radio-inline">
                    <?php if (!$displaylocation) { ?>
                    <input type="radio" name="displaylocation" value="0" checked="checked" />
                    <?php echo $text_displaylocation1; ?>
                    <?php } else { ?>
                    <input type="radio" name="displaylocation" value="0" />
                    <?php echo $text_displaylocation1; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if ($displaylocation) { ?>
                    <input type="radio" name="displaylocation" value="1" checked="checked" />
                    <?php echo $text_displaylocation2; ?>
                    <?php } else { ?>
                    <input type="radio" name="displaylocation" value="1" />
                    <?php echo $text_displaylocation2; ?>
                    <?php } ?>
                  </label>
                </div>
               <label class="col-sm-12 control-label" for="input-bundle" style="text-align:left;"><?php echo $text_bundle; ?></label>
               <div class="col-sm-12">
                <select id="input-bundle" name="bundle" class="form-control">
                    <?php if ($bundle) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
          </div>
        </div>
    </form>
  </div>
</div>
</div>
<?php echo $footer; ?>
</div>
<script type="text/javascript" src="view/javascript/jquery/remodal.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' async rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="view/stylesheet/imdev.css">
<script type="text/javascript">
$('input[name=\'productname\']').autocomplete({
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
    $('input[name=\'productname\']').val('');
    
    $('#product' + item['value']).remove();
    
    $('#product').append('<div id="product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="primaryproducts[]" value="' + item['value'] + '" /></div>'); 
  }
});

$('#product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

</script>

<script type="text/javascript">
$('input[name=\'sproductname\']').autocomplete({
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
    $('input[name=\'sproductname\']').val('');
    
    $('#sproduct' + item['value']).remove();
    
    $('#sproduct').append('<div id="sproduct' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="secondaryproducts[]" value="' + item['value'] + '" /></div>'); 
  }
});

$('#sproduct').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
$('.date').datetimepicker({
  pickTime: false
});
</script>