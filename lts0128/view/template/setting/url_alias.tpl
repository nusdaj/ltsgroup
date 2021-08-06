<?php echo $header; ?><?php echo $column_left; ?>

<?php /* AJ Apr 21: Don't like the implementation of the original code. It's bad to delete all records and then insert them back. Instead, we embed a hidden flag, "action" to indicate none/update/delete/add */ ?>
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
                                    <td class="text-left"><?php echo $column_url_alias_id; ?></td>
                                    <td class="text-left"><?php echo $column_route; ?></td>
                                    <td class="text-left"><?php echo $column_keyword; ?></td>
                                    <td class="text-left">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php if ($urlaliases) { ?>
                                    <?php foreach ($urlaliases as $key => $urlalias) { ?>
                                        
                                        <tr id="urla_row_<?php echo $key;?>">
                                            <td class="text-left"><input type="hidden" name="urla_<?php echo $urlalias['url_alias_id'];?>_id" value="<?php echo $urlalias['url_alias_id'];?>" class="form-control"><?php echo $urlalias['url_alias_id'];?></td>
                                            <td class="text-left"><input type="text" name="urla_<?php echo $urlalias['url_alias_id'];?>_route" value="<?php echo $urlalias['query'];?>" placeholder="Route:" class="form-control"></td>
                                            <td class="text-left"><input type="text" name="urla_<?php echo $urlalias['url_alias_id'];?>_keyword" value="<?php echo $urlalias['keyword'];?>" placeholder="Keyword:" class="form-control"></td>
                                            <td class="text-left">
                                                <input type="hidden" name="urla_<?php echo $urlalias['url_alias_id'];?>_action" value="none" class="form-control">
                                                <a href="#remove" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Remove"><i class="fa fa-minus-circle"></i></a>
                                            </td>
                                        </tr>
                                        
                                    <?php } ?>
                                <?php } else { ?>
                                    
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
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
        
        html += '<tr id="urla_row_' + url_alias_row + '">';
        html += '  <td class="text-left"><input type="hidden" name="add_urla_' + url_alias_row + '_id" value="0" class="form-control">&nbsp;</td>'; 
        html += '  <td class="text-left"><input type="text" name="add_urla_' + url_alias_row + '_route" value="" placeholder="Route:" class="form-control" /></td>';
        html += '  <td class="text-left"><input type="text" name="add_urla_' + url_alias_row + '_keyword" value="" placeholder="Keyword:" class="form-control" /></td>';
        html += '  <td class="text-left">';
        html += '  <input type="hidden" name="add_urla_' + url_alias_row + '_action" value="add" class="form-control">';
        html += '  <a href="#remove" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td>';
        html += '</tr>';
        
        $('#url-alias tbody').append(html);
        
        url_alias_row++;
        
        return false;
    });
    
    // AJ Apr 21: instead of remove(); use hide() & set the flag "add" in the same time.
    $('#url-alias tbody').on('click', 'tr a[href="#remove"]', function() {
        var row = $(this).closest('tr');
        row.hide();  // hide before change. because hidden input doesn't trigger change event.
        row.find(':input:last').val('delete');
        
        return false;
    });

    // AJ Apr 22: trigger when content of input fields (not hidden ones) changed. Set the relevant flag to "update", if the value is "none"
    $('input').change(function() {
        var input = $(this).closest('tr').find(':input:last');
        if (input.value = "none") {
            input.val("update");
            // alert("changed!");
        }
    })
//--></script>
<?php echo $footer; ?> 