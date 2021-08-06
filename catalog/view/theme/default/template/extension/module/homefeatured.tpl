<?php if($category) { ?>
	<h2><?= $title; ?></h2>
	<div class="fcathome">
		<?php $cnt=1; foreach($category as $cat) { ?>
			<div class="item <?php if($cnt%2==0) echo 'reverse'; ?>" style="background: url('image/catalog/slicing/homepage/<?= $cat['background']; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
				<img src="image/<?= $cat['image']; ?>" alt="<?= $cat['title']; ?>" class="img-responsive">
				<div class="text">
					<h5>Featured Category</h5>
					<h3><?= $cat['title']; ?></h3>
					<a href="<?= $cat['link']; ?>" class="btn btn-primary btnwhite"><?= $cat['label']; ?></a>
				</div>
			</div>
		<?php $cnt++; } ?>
	</div>
<?php } ?>