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