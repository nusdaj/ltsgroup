<div class="row">
	<div class="module-instagram">
		<!-- AJ: begin, added Apr 6: display heading title --> 
		<h2 style="color:black; text-align:center; margin:30px 0; font-weight:900; font-size:32px;">Recent Projects</h2>
		<!-- AJ: end, added Apr 6 --> 
		<?php if(!empty($instagrams)) {?>
		<div class="instagram">
			<?php foreach ($instagrams as $instagram){ ?>
			<div class="item <?php echo $hover_effect;?> relative">
				<a href="<?php echo $instagram['href'];?>" target="_blank" data-like="<?php echo $instagram['likes'];?>" title="<?php echo $instagram['text'];?>">
					<div style="background-image:url('<?php echo $instagram['img'];?>'); background-size:cover; background-position:center; background-repeat:no-repeat; padding-bottom:100%;"></div>
					<div class="overlay absolute">
						<img src="image/catalog/slicing/homepage/icon_hp-ig.png" alt="<?= $entry_instagram; ?>">
						<div class="followus">Follow us</div>
						<div class="igname upper"><?= $entry_instagram; ?></div>
					</div>
				</a>
			</div>
			<?php } ?>

		</div>
		<?php } ?>
	</div>
</div>
<style>
	.module-instagram .slick-prev:before,
	.module-instagram .slick-next:before {
		color: <?php echo $color;
		?>;
	}

	.module-instagram h4 {
		text-align: <?php echo $text_align;
		?>
	}

	.instagram .item .fa:before {
		color: <?php echo $heart_color;
		?>
	}

	.instagram .item a:before {
		color: <?php echo $heart_text_color;
		?>
	}

	<?php if($center_mode): ?>.slick-slide {
		opacity: .2;
		transition: opacity .3s linear 0s;
	}

	.slick-slide.slick-active.slick-center {
		opacity: 1;
	}

	<?php endif;
	?>
</style>
<script>
	$('.module-instagram .instagram').slick({
		slidesToShow: <?php echo $slidesToShow;?>,
		slidesToScroll: <?php echo $slidesToScroll ?>,
		autoplay: <?php echo $autoplay; ?>,
		autoplaySpeed: <?php echo $autoplaySpeed; ?>,
		dots: <?php echo $dots; ?>,
		arrows: <?php echo $arrows; ?>,
		<?php echo ($center_mode) ? "centerMode : $center_mode," : ''; ?>
		responsive: [{
				breakpoint: 1024,
				settings: {
					slidesToShow: <?php echo $slidesToShow; ?>,
					slidesToScroll: <?php echo $slidesToScroll ?>,
					infinite: true,
					arrows: false
				}
			},
			{
				breakpoint: 621,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 1,
					arrows: false,
					dots: true,
				}
			},
			{
				breakpoint: 541,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1,
					arrows: false,
					dots: true,
				}
			}
		]
	});
</script>