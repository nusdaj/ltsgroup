<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?= $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
  <?php if($newspanel){ ?>
	<?= $newspanel; ?><br />
  <?php } ?>
    <?php if (isset($error_facebook_sync)) {?>
    <div class="alert alert-danger hidden"><i class="fa fa-exclamation-circle"></i> <?= $error_facebook_sync; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($warning_cron_stock) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?= $warning_cron_stock; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php foreach ($rows as $row) { ?>
    <div class="row">
      <?php foreach ($row as $dashboard_1) { ?>
      <?php $class = 'col-lg-' . $dashboard_1['width'] . ' col-md-3 col-sm-6'; ?>
      <?php foreach ($row as $dashboard_2) { ?>
      <?php if ($dashboard_2['width'] > 3) { ?>
      <?php $class = 'col-lg-' . $dashboard_1['width'] . ' col-md-12 col-sm-12'; ?>
      <?php } ?>
      <?php } ?>
      <div class="<?= $class; ?>"><?= $dashboard_1['output']; ?></div>
      <?php } ?>
    </div>
    <?php } ?>
  </div>
</div>
<?= $footer; ?>