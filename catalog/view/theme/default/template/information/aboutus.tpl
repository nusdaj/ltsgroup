<?php echo $header; ?>
<div class="bg">
<div class="container"> 
	<?php echo $content_top; ?>
	<ul class="breadcrumb">
	  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	  <?php } ?>
	</ul>
  
  	<div class="content">
		<h2>About LTS Group</h2>
	  	<div class="about-lts">
	  	    <?php if($icon1) { ?>
	  		<div class="text-center">
	  		    <a href="<?= $iicon1; ?>">
	  			<div>
	  				<img src="image/<?= $icon1; ?>" alt="<?= $ititle1; ?>" class="img-responsive">
	  				<p><?= $ititle1; ?></p>
	  			</div>
	  			</a>
	  		</div>
	  		<?php } ?>
	  		<?php if($icon2) { ?>
	  		<div class="text-center">
	  		    <a href="<?= $iicon2; ?>">
	  			<div>
	  				<img src="image/<?= $icon2; ?>" alt="<?= $ititle2; ?>" class="img-responsive">
	  				<p><?= $ititle2; ?></p>
	  			</div>
	  			</a>
	  		</div>
	  		<?php } ?>
	  		<?php if($icon3) { ?>
	  		<div class="text-center">
	  		    <a href="<?= $iicon3; ?>">
	  			<div>
	  				<img src="image/<?= $icon3; ?>" alt="<?= $ititle3; ?>" class="img-responsive">
	  				<p><?= $ititle3; ?></p>
	  			</div>
	  			</a>
	  		</div>
	  		<?php } ?>
	  		<?php if($icon4) { ?>
	  		<div class="text-center">
	  		    <a href="<?= $iicon4; ?>">
	  			<div>
	  				<img src="image/<?= $icon4; ?>" alt="<?= $ititle4; ?>" class="img-responsive">
	  				<p><?= $ititle4; ?></p>
	  			</div>
	  			</a>
	  		</div>
	  		<?php } ?>
	  		<?php if($icon5) { ?>
	  		<div class="text-center">
	  		    <a href="<?= $iicon5; ?>">
	  			<div>
	  				<img src="image/<?= $icon5; ?>" alt="<?= $ititle5; ?>" class="img-responsive">
	  				<p><?= $ititle5; ?></p>
	  			</div>
	  			</a>
	  		</div>
	  		<?php } ?>
	  	</div>

	  	<div class="about-content text-center">
	  		<div>
		  		<h3 id="target"><?= $title1; ?></h3>
		  		<div><?= $description1; ?></div>
	  		</div>
	  		<div>
		  		<h3 id="choice"><?= $title2; ?></h3>
		  		<div><?= $description2; ?></div>
	  		</div>
	  		<div>
		  		<h3 id="improveroi"><?= $title3; ?></h3>
		  		<div><?= $description3; ?></div>
	  		</div>
	  	</div>

	  	<a name="aboutservices"></a>
	  	<div class="about-services text-center">
	  		<h3 id="services">Services</h3>
	  		<div class="abt-services-flex">
	  			<?php foreach( $services as $s ) { ?>
	  			<div>
	  				<div>
	  					<img src="image/<?= $s['icon'] ?>" class="img-responsive" alt="<?= $s['title'] ?>">
		  				<p class="services-title"><?= $s['title'] ?></p>
		  				<div class="abt-services-desc"><?= html_entity_decode($s['description']); ?></div>
	  				</div>
	  			</div>
	  			<?php } ?>
	  		</div>
	  	</div>

	  	<div class="about-clients text-center">
	  		<h3 id="clients">Clients</h3>		
	  		<div class="about-clients-flex">
	  			<?php foreach( $client as $c ) { ?>
	  			<div>
	  				<div>
	  					<img src="image/<?= $c['icon'] ?>" class="img-responsive" alt="Client">
	  				</div>
	  			</div>
	  			<?php } ?>
	  		</div>
	  	</div>
  	</div>
</div>
</div>
<?php echo $footer; ?>