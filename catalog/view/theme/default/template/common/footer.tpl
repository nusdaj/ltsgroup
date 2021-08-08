<div class="floatingicos">
	<?php /* AJ Apr 16: add a icon links to catalogue tree. added tooltips to all 3 icons  */ ?>
    <a href="<?= $whatsapplink; ?>" class="floatingwhatsapp" target="_target"><img src="image/catalog/slicing/wa.png" alt="whatsapp" title="whatsapp live chat"></a>
    <a href="<?= $emaillink; ?>" class="floatingemail" target="_target"><img src="image/catalog/slicing/email.png" alt="email" title="send an e-mail"></a>
	<a href="<?= $cataloglink; ?>" class="floatingemail"><img src="image/catalog/slicing/caticon1.png" alt="catalogue" title="browse catalogue"></a>
</div>
<div id="footer-area">
<footer>
	<div class="container">
		<div class="footer-upper-contet">
			<?php if ($menu) { ?>
				<?php foreach($menu as $links){ ?>
				<div class="footer-contact-links">
					<h5>
						<?php if($links['href'] != '#'){ ?>
						<?= $links['name']; ?>
						<?php }else{ ?>
						<a href="<?= $links['href']; ?>" 
							<?php if($links['new_tab']){ ?>
								target="_blank"
							<?php } ?>
							>
							<?= $links['name']; ?></a>
						<?php } ?>
					</h5>
					<?php if($links['child']){ ?>
					<div class="footer-information">
						<ul class="list-unstyled">
						<?php foreach ($links['child'] as $each) { ?>
						<li><a href="<?= $each['href']; ?>"
							<?php if($each['new_tab']){ ?>
								target="_blank"
							<?php } ?>
							
							>
								<?= $each['name']; ?></a></li>
						<?php } ?>
						</ul>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			<?php } ?>

			<div class="footer-contact-info">
				<div>
					<h5>Our Location</h5>
					<p class="m0">
					<ul class="list-unstyled">
						<li><?= $store; ?></li>
						<li><?= $address; ?></li>
						<li><span>Telephone:</span> <a href="tel:<?= $telephone; ?>" ><?= $telephone; ?></a></li>
						<li>
							<?php if($fax){ ?>
							<span><?= $text_fax; ?></span>: <?= $fax; ?><br/>
							<?php } ?>
						</li>
						<li><span>Email:</span> <a href="mailto:<?= $email; ?>" ><?= $email; ?></a></li>
					</ul>
					</p>
				</div>

				<div>
					<h5>Keep Up to Date</h5>
					<?php if($social_icons){ ?>
					<div class="footer-social-icons">
						<?php foreach($social_icons as $icon){ ?>
						<a href="<?= $icon['link']; ?>" title="<?= $icon['title']; ?>" alt="
									<?= $icon['title']; ?>" target="_blank">
							<img src="<?= $icon['icon']; ?>" title="<?= $icon['title']; ?>" class="img-responsive" alt="<?= $icon['title']; ?>" />
						</a>
						<?php } ?>
					</div>
					<?php } ?>
					<?php if($mailchimp){ ?>
						<div class="newsletter-section text-center">
							<?= $mailchimp; ?>
						</div>
					<?php } ?>
				</div>
			</div>

		</div>
	</div>
<div class="footbot">
	<div class="container">
		<div class="footer-bottom row">
			<div class="col-xs-12 col-sm-6">
				<p><?= $powered; ?></p>
			</div>
			<div class="col-xs-12 col-sm-6 text-sm-right">
				<p><?= $text_fcs; ?></p>
			</div>
		</div>
	</div>
</div>
</footer>
</div>
<div id="ToTopHover" ></div>


      <?php /* AJ Aug 8: move the modal dialogue to the footer. Hopefully, it should remove the redunctancy occured
	  AJ Apr 12, begin: add Modal window. Copy from Category.tpl; Apr 14, begin: change the error from hint & let validation done at browser */ ?>    
      <!-- Modal -->
      <div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <p class="productmodal-title" id="exampleModalLabel">Enquire Now</p>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" >
                <div class="contact-body">
                  <div class="form-group required">
                    <input type="text" name="name" value="<?= $name; ?>" id="input-name" class="form-control" placeholder="<?= $entry_name; ?>" minlength="3" maxlength="32" /> 
                    <?php if ($error_name) { ?>
                      <div class="text-danger"><?= $error_name; ?></div>
                    <?php } ?>               
                  </div>
                  <div class="form-group required">
                    <input type="email" name="email" value="<?= $email; ?>" id="input-email" class="form-control" placeholder="<?= $entry_email; ?>" />
                    <?php if ($error_email) { ?>
                      <div class="text-danger"><?= $error_email; ?></div>
                    <?php } ?>
                  </div>
                  <div class="form-group">
                    <input type="tel" name="telephone" value="<?= $telephone; ?>" id="input-telephone" class="form-control input-number" placeholder="<?= $entry_telephone; ?>" />
                    <?php if ($error_telephone) { ?>
                      <div class="text-danger"><?= $error_telephone; ?></div>
                    <?php } ?>
                  </div>
                  <div class="form-group hidden">
                    <input type="text" name="subject" id="input-subject" class="form-control" value="Enquiry Now from Featured Product in home page" />
                  </div>
                  <div class="form-group">
                    <input type="text" name="featuredProduct" id="input-products" class="form-control" value="<?php $featuredProduct; ?>" readonly />
                  </div>
                  <div class="form-group required">
                    <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control" placeholder="<?= $entry_enquiry; ?>" minlength="10" maxlength="300"><?= $enquiry; ?></textarea>
                    <?php if ($error_enquiry) { ?>
                      <div class="text-danger"><?= $error_enquiry; ?></div>
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


<?php if(isset($update_price_status) && $update_price_status) { ?>
	<script type="text/javascript">
    $(".product-inputs input[type='checkbox']").click(function() {
      var product_id = $(this).data('product-id');
      changePrice(product_id);
    });
    $(".product-inputs input[type='radio']").click(function() {
      var product_id = $(this).data('product-id');
      changePrice(product_id);
    });
    $(".product-inputs select").change(function() {
      var product_id = $(this).data('product-id');
      changePrice(product_id);
    });
    $(".input-number").blur(function() {
      var product_id = $(this).data('product-id');
      changePrice(product_id);
    });
    $(".input-number").parent(".input-group").find(".btn-number").click(function() {
      var product_id = $(this).data('product-id');
      changePrice(product_id);
    });
    function changePrice(product_id) {
      $.ajax({
        url: 'index.php?route=product/product/updatePrice&product_id=' + product_id,
        type: 'post',
        dataType: 'json',
        data: $('#product-'+ product_id + ' input[name=\'quantity\'], #product-'+ product_id + ' select, #product-'+ product_id + ' input[type=\'checkbox\']:checked, #product-'+ product_id + ' input[type=\'radio\']:checked'),
        success: function(json) {
          $('.alert-success, .alert-danger').remove();
          if(json['new_price_found']) {
            $('.product-price-'+product_id+' .price .price-new').html(json['total_price']);
            $('.product-price-'+product_id+' .price .price-tax').html(json['tax_price']);
          } else {
            $('.product-price-'+product_id+' .price .price-new').html(json['total_price']);
            $('.product-price-'+product_id+' .price .price-tax').html(json['tax_price']);
          }
        }
      });
    }
	</script>
<?php } ?>
<script>AOS.init({
	once: true
});</script>

<!-- Accordion -->
<script>
var acc = document.getElementsByClassName("megaaccordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
<!-- Accordion -->

<?php 
/* extension bganycombi - Buy Any Get Any Product Combination Pack */
echo $bganycombi_module; 
?>
</body></html>