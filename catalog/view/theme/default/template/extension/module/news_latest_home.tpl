<?php if($article) { ?>
<h2><?= $heading_title; ?></h2>
<div class="homenewslick">
	<?php foreach ($article as $articles) { ?>
		<div class="artblock">			
				<?php if ($articles['thumb']) { ?>
					<a href="<?php echo $articles['href']; ?>"><img class="img-responsive w100" src="<?php echo $articles['thumb']; ?>" alt="<?php echo $articles['name']; ?>" /></a>
				<?php } ?>
				<div class="artblock-content">
					<?php if ($articles['name']) { ?>
					<div class="name"><a href="<?php echo $articles['href']; ?>"><?php echo $articles['name']; ?></a></div>
					<?php } ?>
					<?php if ($articles['description']) { ?>
						<div class="description"><?php echo $articles['description']; ?></div>
					<?php } ?>			
					<?php if ($articles['button']) { ?>
						<div class="blog-button">
							<a class="button" href="<?php echo $articles['href']; ?>"><?php echo $button_more; ?></a>
							<img src="image/catalog/slicing/homepage/read-more-arrow.png" alt="">
						</div>
					<?php } ?>
				</div>
			</div>
	<?php } ?>
</div>

<script type="text/javascript">
$(".homenewslick").slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 2,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 621,
      settings: {
        slidesToShow: 1,
      }
    }
  ],
  prevArrow: "<img src='image/catalog/slicing/homepage/arrow-prev.png' alt='prev' class='slickarr left'/>",
  nextArrow: "<img src='image/catalog/slicing/homepage/arrow-next.png' alt='next' class='slickarr right'/>",
});
</script>
<?php } ?>