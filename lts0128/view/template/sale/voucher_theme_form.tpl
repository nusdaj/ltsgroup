<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="$('#form-theme-voucher').submit();"><i class="fa fa-save"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-theme-voucher" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="voucher_theme_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($voucher_theme_description[$language['language_id']]) ? $voucher_theme_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
                <div class="input-group" style="width:100%;"><span><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                  <textarea name="voucher_theme_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" rows="8" class="form-control"><?php echo isset($voucher_theme_description[$language['language_id']]) ? $voucher_theme_description[$language['language_id']]['description'] : ''; ?></textarea>
                </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
              <?php if ($error_image) { ?>
              <div class="text-danger"><?php echo $error_image; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
            <div class="col-sm-10">
              <select name="type" id="input-type" class="form-control">
              <?php foreach($type_arr as $tk => $tv) { ?>
                <option value="<?= $tk ?>"<?= $tk == $type ? ' selected="selected"' : '' ?>><?= $tv ?></option>
              <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amount" value="<?php echo $amount; ?>" id="input-amount" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-seo_keyword">SEO Keyword</label>
            <div class="col-sm-10">
              <input type="text" name="seo_keyword" value="<?php echo $seo_keyword; ?>" id="input-seo_keyword" class="form-control" />
            </div>
          </div>
		      <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort_order">Sort Order</label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" id="input-sort_order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>