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
      <?php if ($orders) { ?>
        
        <table class="table table-hover">
          <thead>
            <tr>
              <td class="text-left"><?= $column_order_id; ?></td>
              <td class="text-left"><?= $column_date_added; ?></td>
              <td class="text-left"><?= $column_total; ?></td>
              <td class="text-left"><?= $column_status; ?></td>
              <td class="text-center"><?= $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-left">#<?= $order['order_id']; ?></td>              
              <td class="text-left">
                <small class="bold visible-xs"><?= $column_date_added; ?><br/></small>
                <?= $order['date_added']; ?></td>
              <td class="text-left">
                <small class="bold visible-xs"><?= $column_total; ?><br/></small>
                <?= $order['total']; ?></td>
              <td class="text-left">
                <small class="bold visible-xs"><?= $column_status; ?><br/></small>
                <?= $order['status']; ?></td>
              <td class="text-center">
                  <small class="bold visible-xs"><?= $column_action; ?><br/></small>
                <a href="<?= $order['view']; ?>" class="inline-link"><?= $button_view; ?></a>
                
                <a href="<?= $order['reorder']; ?>" class="inline-link esc" data-toggle="modal-content" 
                  data-title="<?= $button_reorder; ?>: <?= $column_order_id; ?> #<?= $order['order_id']; ?>" >
                  <?= $button_reorder; ?>
                </a>
                
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      <div class="row">
        <div class="col-sm-6 text-left"><?= $pagination; ?></div>
        <div class="col-sm-6 text-right"><?= $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?= $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?= $continue; ?>" class="btn btn-primary"><?= $button_continue; ?></a></div>
      </div>
      </div>
    <?= $column_right; ?></div>
    <?= $content_bottom; ?>
</div>
<?= $footer; ?>
