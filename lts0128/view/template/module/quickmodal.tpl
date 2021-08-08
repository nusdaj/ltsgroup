<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-quickmodal" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_alert; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-quickmodal" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-selector"><?php echo $entry_selector; ?></label>
            <div class="col-sm-10">
              <input type="text" name="quickmodal_selector" value="<?php echo $quickmodal_selector; ?>" placeholder=".product-layout" id="input-selector" class="form-control" />
            </div>
          </div> 

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-style"><?php echo $entry_b_style; ?></label>
            <div class="col-sm-10 controls">
              <input type="text" name="quickmodal_b_style" value="<?php echo $quickmodal_b_style; ?>" placeholder="btn btn-primary btn-lg btn-block" id="input-style" class="form-control" />
            </div>
          </div> 

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-text"><?php echo $entry_b_text; ?></label>
            <div class="col-sm-10 controls">
              <input type="text" name="quickmodal_b_text" value="<?php echo $quickmodal_b_text; ?>" placeholder="Quick View" id="input-text" class="form-control" />
            </div>
          </div> 

          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-assign"><span data-toggle="tooltip" title="<?php echo $help_quickmodal; ?>"><?php echo $entry_assign; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="assign" value="" placeholder="<?php echo $entry_assign; ?>" id="input-assign" class="form-control" />
                  <div id="quickmodal-layout" class="well well-sm" style="height: 150px; overflow: auto;">

                    <?php if (!empty($quickmodal_layouts)) { foreach ($quickmodal_layouts  as $layout_id => $layout_name) { ?>
                    <div id="quickmodal-layout<?php echo $quickmodal_layout['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $layout_name; ?>
                      <input type="hidden" name="quickmodal_layouts[<?php echo $layout_id; ?>]" value="<?php echo $layout_name; ?>" />
                    </div>
                    <?php }} ?>
                  </div>
                </div>
              </div>

           <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="quickmodal_status" id="input-status" class="form-control">
                <?php if ($quickmodal_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-status">CSS Editor</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="textarea" style="min-height:600px;font-size:15px;" name="quickmodal_css"><?php echo $quickmodal_css;?></textarea>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('input[name=\'assign\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=module/quickmodal/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['layout_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'assign\']').val('');
    
    $('#quickmodal-layout' + item['value']).remove();
    
    $('#quickmodal-layout').append('<div id="quickmodal-layout' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="quickmodal_layouts[' + item['value'] + ']" value="' + item['label'] + '" /></div>'); 
  }
});

$('#quickmodal-layout').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
</script>

<?php echo $footer; ?>