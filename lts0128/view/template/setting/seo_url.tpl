<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-information" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store">
                    <div class="table-responsive">
                        <table id="url-alias" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td class="text-left"><?php echo $column_route; ?></td>
                                    <td class="text-left"><?php echo $column_keyword; ?></td>
                                    <td class="text-right">&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php if ($seourls) { ?>
                                    <?php foreach ($seourls as $key => $seourl) { ?>
                                        
                                        <tr id="url_alias_row_<?php echo $key;?>">
                                            <td class="text-left"><input type="text" name="url_alias[<?php echo $key;?>][route]" value="<?php echo $seourl['query'];?>" placeholder="Route:" class="form-control"></td>
                                            <td class="text-left"><input type="text" name="url_alias[<?php echo $key;?>][keyword]" value="<?php echo $seourl['keyword'];?>" placeholder="Keyword:" class="form-control"></td>
                                            <td class="text-left"><a href="#remove" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Remove"><i class="fa fa-minus-circle"></i></a></td>
                                        </tr>
                                        
                                    <?php } ?>
                                    <?php } else { ?>
                                    
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-left"><a href="#add" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add URL Alias"><i class="fa fa-plus-circle"></i></a></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    var url_alias_row = <?php echo $key+1;?>;
    
    $('a[href="#add"]').on('click', function() {
        var html = '';
        
        html += '<tr id="url_alias_row_' + url_alias_row + '">';
        html += '  <td class="text-left"><input type="text" name="url_alias[' + url_alias_row + '][route]" value="" placeholder="Route:" class="form-control" /></td>';
        html += '  <td class="text-left"><input type="text" name="url_alias[' + url_alias_row + '][keyword]" value="" placeholder="Keyword:" class="form-control" /></td>';
        html += '  <td class="text-left"><a href="#remove" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td>';
        html += '</tr>';
        
        $('#url-alias tbody').append(html);
        
        url_alias_row++;
        
        return false;
    });
    
    $('#url-alias tbody').on('click', 'tr a[href="#remove"]', function() {
        $(this).closest('tr').remove();
        
        return false;
    });
//--></script>
<?php echo $footer; ?> 