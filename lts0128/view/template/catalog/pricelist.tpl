<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" data-toggle="tooltip" title="<?php echo $button_catalogue; ?>" class="btn btn-primary" onclick="$('#booktype').val('catalogue'); $('#form-category').submit();"><i class="fa fa-book"></i></button>
				<button type="button" data-toggle="tooltip" title="<?php echo $button_pricelist; ?>" class="btn btn-success" onclick="$('#booktype').val('pricelist'); $('#form-category').submit();"><i class="fa fa-usd"></i></button>
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
        <div class="well">
			<div class="row">
				<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
					<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
				</div>
					<button type="button" id="button-clear" class="btn btn-default pull-right"><i class="fa fa-refresh"></i> <?php echo $button_clear; ?></button>
					<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
        </div>
				<form action="<?php echo $generate; ?>" method="post" enctype="multipart/form-data" id="form-category">
					<input type=hidden name="booktype" id="booktype" value="" />
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left"><?php if ($sort == 'name') { ?>
										<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
									<?php } ?></td>
									<td class="text-right"><?php if ($sort == 'sort_order') { ?>
										<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
									<?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
									<td class="text-right"><?php echo $column_URL; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php if ($categories) { ?>
									<?php foreach ($categories as $category) { ?>
										<tr>
											<td class="text-center"><?php if (in_array($category['category_id'], $selected)) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
												<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
											<?php } ?></td>
											<td class="text-left"><?php echo $category['name']; ?></td>
											<td class="text-right"><?php echo $category['sort_order']; ?></td>
		                  <td class="text-left"><?= $category['status'] == 'Enabled'?'Enabled':'Disabled'; ?>
		                  <?php /*
		                    <label class="switch">
		                      <input type="checkbox" value="<?=$category['category_id']?>" <?= $category['status'] == 'Enabled'?'checked':''; ?> class="toggle-status">
		                      <span class="slider round"></span>
		                    </label>      
		                    */ ?>
		                  </td>
											<td class="text-right">

												<?php if($category['shref']){ ?>
													<a data-href = "<?php echo $category['shref']; ?>" onclick = 'copythis(this);' data-toggle="tooltip" title="Click to copy Url" class="btn btn-warning">
													<i class="fa fa-copy"></i>&nbsp;&nbsp;Promotional&nbsp;Url</a>
												<?php } ?>

												<?php if($category['href']){ ?>
													<a data-href = "<?php echo $category['href']; ?>" onclick = 'copythis(this);' data-toggle="tooltip" title="Click to copy Url" class="btn btn-success">
													<i class="fa fa-copy"></i>&nbsp;&nbsp;Url</a>
												<?php } ?>
												
											</td>
										</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>

			</div>
		</div>
	</div>


<script type="text/javascript"><!--
  $('#button-clear').on('click', function() {
    var url = 'index.php?route=catalog/pricelist&token=<?php echo $token; ?>';
    window.location= url;
  });
  $('#button-filter').on('click', function() {
  	var url = 'index.php?route=catalog/pricelist&token=<?php echo $token; ?>';

  	var filter_name = $('input[name=\'filter_name\']').val();

  	if (filter_name) {
  		url += '&filter_name=' + encodeURIComponent(filter_name);
  	}

  	location = url;
  });

	$('.toggle-status').on('click', function() {
	  $.ajax({url: 'index.php?route=catalog/category/changeStatus&token=<?php echo $token; ?>&category_id=' + $(this).val()});
	});
</script>


</div>
<?php echo $footer; ?>