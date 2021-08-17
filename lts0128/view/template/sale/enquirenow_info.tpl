<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $text_enquiry_detail; ?></h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_id; ?>" class="btn btn-info btn-xs"><i class="fa fa-hand-o-right fa-fw"></i></button></td>
                <td> <?php echo $id; ?> </td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_date_added; ?>" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                <td><?php echo $date_added; ?></td>
              </tr>
              <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_product; ?>" class="btn btn-info btn-xs"><i class="fa fa-file-powerpoint-o fa-fw"></i></button></td>
              <td><a href="<?php echo $product_link; ?>" target="_blank"><?php echo $product; ?></a></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $text_customer_detail; ?></h3>
          </div>
          <table class="table">
            <tr>
              <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_customer; ?>" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
              <td> <?php echo $name; ?> </td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_email; ?>" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
              <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_telephone; ?>" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
              <td><?php echo $telephone; ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_message_detail; ?></h3>
          </div>
          <table class="table table-bordered">
            <tbody>
              <tr>
                <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_message; ?>" class="btn btn-info btn-xs"><i class="fa fa-file-text-o fa-fw"></i></button></td>
                <td><?php echo $message; ?></td>              
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_pricelist; ?></h3>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td><?php echo $text_quantity; ?></td>
              <?php foreach ($pricelist as $key=>$value) { ?>
              <td><?php echo $key; ?></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <tr>
            <td><?php echo $text_price; ?></td>
            <?php foreach ($pricelist as $key=>$value) { ?>
            <td><?php echo $value; ?></td>
            <?php } ?>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_printing_cost; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <?php foreach ($tabs as $index => $tab) { ?>
          <li <?php if ($index==0) echo "class='active'";  ?>><a href="#tab-<?php echo $tab['code']; ?>" data-toggle="tab"><?php echo $tab['title']; ?></a></li>
          <?php } ?>
         </ul>

        <div class="tab-content">
          <?php foreach ($tabs as $index => $tab) { ?>
          <div class="tab-pane <?php if ($index==0) echo ' active ' ?> " id="tab-<?php echo $tab['code']; ?>"><?php echo $tab['content']; ?></div>
          <?php } ?>
        </div>
      </div>
    </div>    
  </div>
</div>

<?php echo $footer; ?> 
