<h1>Modal Enquiry</h1>

<?php /* AJ Aug 8: move the modal dialogue to the footer. Hopefully, it should remove the redunctancy occured
	  AJ Apr 12, begin: add Modal window. Copy from Category.tpl; Apr 14, begin: change the error from hint & let validation done at browser */ ?>
<!-- Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="productmodal-title" id="exampleModalLabel">Enquire Now</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="contact-body">
                        <div class="form-group required">
                            <input type="text" name="name" value="<?= $name; ?>" id="input-name" class="form-control"
                                placeholder="<?= $entry_name; ?>" minlength="3" maxlength="32" />
                            <?php if ($error_name) { ?>
                            <div class="text-danger">
                                <?= $error_name; ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group required">
                            <input type="email" name="email" value="<?= $email; ?>" id="input-email"
                                class="form-control" placeholder="<?= $entry_email; ?>" />
                            <?php if ($error_email) { ?>
                            <div class="text-danger">
                                <?= $error_email; ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="telephone" value="<?= $telephone; ?>" id="input-telephone"
                                class="form-control input-number" placeholder="<?= $entry_telephone; ?>" />
                            <?php if ($error_telephone) { ?>
                            <div class="text-danger">
                                <?= $error_telephone; ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group hidden">
                            <input type="text" name="subject" id="input-subject" class="form-control"
                                value="Enquiry Now from Featured Product in home page" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="featuredProduct" id="input-products" class="form-control"
                                value="<?php $featuredProduct; ?>" readonly />
                        </div>
                        <div class="form-group required">
                            <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"
                                placeholder="<?= $entry_enquiry; ?>" minlength="10"
                                maxlength="300"><?= $enquiry; ?></textarea>
                            <?php if ($error_enquiry) { ?>
                            <div class="text-danger">
                                <?= $error_enquiry; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="contact-footer text-center">
                            <?= $captcha; ?>
                            <input class="btn btn-primary pull-sm-right btn-submit" type="submit" value="Submit" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- AJ Apr 12, end: add Modal window; Apr 14, end: hint & validation at browser -->

<?php /* 
Apr 20, begin: check if validation failed. failed, show the modal dialog */ ?>
<?php if ($validation_failed == true) {
  echo  "<script>  $(function() { $('#input-products').val('$featuredProduct'); $('#enquiryModal').modal('show');  }); </script>";
} ?>


<?php /* AJ AUG 8: moved here from home.tpl. Hopefully, other 4 places can share this part of code
AJ Apr 12, begin: add call to the Modal window */ ?>
<script type="text/javascript">
    function toggleProductModal(product) {
        $("#enquiryModal #input-products").val(product);
    }
</script>
<?php /* AJ Apr 12, end: add call to the Modal window */ ?>