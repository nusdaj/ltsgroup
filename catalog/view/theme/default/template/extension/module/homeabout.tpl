<?php if($services) { ?>
<div class="container">
	<h2><?= $title; ?></h2>
	<div class="subtitle text-center"><?= $subtitle; ?></div>
	<div class="homserviceflex">
		<?php foreach($services as $serv) { ?>
			<div>
				<div>
					<a class="item text-center" href="<?= $href; ?>#aboutservices">
					<img src="image/<?= $serv['icon'] ;?>" alt="<?= $serv['title']; ?>" class="img-responsive">
					<h4><?= $serv['title']; ?></h4>
					</a>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<?php } ?>