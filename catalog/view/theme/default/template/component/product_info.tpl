<div class="product-gutter" id="product-<?=$product_id?>"> <?php /* product option in product component :: add product id to div  */ ?>
	<div class="product-block <?= $out_of_stock; ?> pointer">
		<div class="product-image-block relative" onclick="location.href = '<?= $href; ?>'">
			<?php if($sticker && $sticker['name']){ ?>
			
			<?php if($sticker['image']) { ?>
			    <a title="<?= $name; ?>" class="sticker absolute imgsticker">
    				<img src="image/<?= $sticker['image']; ?>" alt="<?= $name; ?>" class="img-responsive"/>
    			</a>
			<?php } else { ?>
			    <a 
    			href="<?= $href; ?>" 
    			title="<?= $name; ?>" 
    			class="sticker absolute" 
    			style="color: <?= $sticker['color']; ?>; background-color: <?= $sticker['background-color']; ?>">
    				<?= $sticker['name']; ?>
    			</a>
			<?php } ?>
			
			<?php } ?>
			<?php if($show_special_sticker){ ?>
			<a 
			href="<?= $href; ?>" 
			title="<?= $name; ?>" 
			class="special-sticker absolute" 
			style="top:<?= $sticker ? '30px' : '0px' ?>; color: #fff; background-color: red;">
				<?= $text_sale; ?>
			</a>
			<?php } ?>
			<a 
				href="<?= $href; ?>" 
				title="<?= $name; ?>" 
				class="product-image image-container relative" >
				<img 
					src="<?= $thumb; ?>" 
					alt="<?= $name; ?>" 
					title="<?= $name; ?>"
					class="img-responsive img1" />
				<?php if($thumb2 && $hover_image_change) { ?>
					<img 
						src="<?= $thumb2; ?>" 
						alt="<?= $name; ?>" 
						title="<?= $name; ?>"
						class="img-responsive img2" style="display: none"/>
				<?php } ?>
				<?php /*if($more_options){ ?>
				<div class="more-options-text absolute position-bottom-center">
					<?= $more_options; ?>
				</div>
				<?php }*/ ?>
			</a>
			<div class="bgoverlay"></div>
			<div class="btn-group product-button">
				<a href="<?= $href; ?>" class="product-view-more">
					<img src="image/catalog/slicing/homepage/icon_nav-search.png" alt="View">
				</a>
			</div>
		</div>
		<div class="prod-category">
			<?= $category; ?>
		</div>
		<div class="product-name">
			<a href="<?= $href; ?>"><?= $name; ?></a>
		</div>

		<div class="product-details product-price-<?=$product_id?>">
			<?php if ($price && !$enquiry) { ?>
				<div class="price">
					<?php if (!$special) { ?>
						<span class="price-new"><?= $discount; ?></span> - <!-- AJ Apr 11, added: display lowest price --> 
						<span class="price-new"><?= $price; ?></span>
					<?php } else { ?>
						<span class="price-new"><?= $discount; ?></span> - <!-- AJ Apr 11, added: display lowest price --> 
						<span class="price-new"><?= $special; ?></span>
						<span class="price-old"><?= $price; ?></span>
					<?php } ?>
					<?php if ($tax) { ?>
						<span class="price-tax"><?= $text_tax; ?> <?= $tax; ?></span>
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<div class="rating">
			<?php $count=0; for ($i = 1; $i <= 5; $i++) { ?>
			<?php if ($rating < $i) { ?>
			<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
			<?php } else { ?>
			<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
			<?php $count++; } ?>
			<?php } ?>
			<span>(<?= $count; ?>)</span>
		</div>

	    <div class="cart-buttons">
	    	<?php /* AJ Apr 11 removed: <a class="btn btn-primary btncartinfo" onclick="cart.add(<?= $product_id; ?>)">Add to Cart</a> */ ?>
	    	<a href="<?= $href ?>" class="btn btn-primary">View More</a>
			<?php /* AJ Apr 13: we add in htmlentities to wrap the 'name' field. this $name caused an error - SyntaxError: '' string literal contains an unescaped line break */ ?>
			<a class="btn btn-primary" data-toggle="modal" data-target="#enquiryModal" onclick="toggleProductModal('<?php echo htmlentities($name) ?>', '<?= $product_id; ?>')">Enquire Now</a>
			
	    </div>

		<?php /* product option in product component */ ?>
	</div>
</div>