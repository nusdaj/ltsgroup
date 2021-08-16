<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-pickpacklist" form="form-enquiry" formaction="<?php echo $pickpacklist; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $text_pickpacklist; ?>" class="btn btn-warning"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-shipping" form="form-enquiry" formaction="<?php echo $shipping; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-enquiry" formaction="<?php echo $invoice; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <button type="button" id="button-add" form="form-enquiry" formaction="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>"  class="btn btn-primary"><i class="fa fa-plus"></i></button>
        <button type="button" id="button-delete" form="form-enquiry" formaction="<?php echo $delete; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" action="" enctype="multipart/form-data" id="form-enquiry">
          <div class="table-responsive">
            <table class="table table-benquiryed table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked); setBtnActive();" /></td>
                  <td class="text-center"><?php echo $column_id; ?></td>
                  <td class="text-center"><?php echo $column_name; ?></td>
                  <td class="text-center"><?php echo $column_email; ?></td>
                  <td class="text-center"><?php echo $column_telephone; ?></td>
                  <td class="text-center"><?php echo $column_product; ?></td>
                  <td class="text-center"><?php echo $column_message; ?></td>
                  <td class="text-center"><?php echo $column_date_added; ?></td>
                  <td class="text-center"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($enquirenows) { ?>
                <?php foreach ($enquirenows as $enquirenow) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($enquirenow['id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $enquirenow['id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $enquirenow['id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $enquirenow['id']; ?></td>
                  <td class="text-left"><?php echo $enquirenow['name']; ?></td>
                  <td class="text-left"><?php echo $enquirenow['email']; ?></td>
                  <td class="text-right"><?php echo $enquirenow['telephone']; ?></td>
                  <td class="text-left"><?php echo $enquirenow['product']; ?></td>
                  <td class="text-left"><?php echo $enquirenow['message']; ?></td>
                  <td class="text-left"><?php echo $enquirenow['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $enquirenow['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
$('#button-pickpacklist, #button-shipping, #button-invoice, #button-add').prop('disabled', true);

$('#button-delete').on('click', function(e) {
	$('#form-enquiry').attr('action', this.getAttribute('formAction'));
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		$('#form-enquiry').submit();
	} else {
		return false;
	}
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
</div>
<?php echo $footer; ?> 