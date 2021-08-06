<div class="box oc-module">
<h3 class="box-heading"><?php echo $headtitle; ?></h3>
<?php if ($showimg == 1) { ?>
<div class="box-content row">
<div class="box-product">     
<?php foreach ($gallalbums as $gallalbum) { ?>
<div class="col-gall-3">
<div class="box-album transition gall<?php echo $boxstyle; ?> alb{{ module }}">
<?php if ($thumblist == 'style3') { ?>  
<div class="caption">
<h4 class="<?php echo $titlepos; ?>"><a href="<?php echo $gallalbum['href']; ?>"><?php echo $gallalbum['name']; ?></a></h4>
</div>        
<?php } ?>      
<div class="image"><a href="<?php echo $gallalbum['href']; ?>"><img src="<?php echo $gallalbum['thumb']; ?>" alt="<?php echo $gallalbum['name']; ?>" title="<?php echo $gallalbum['name']; ?>" class="img-responsive" /></a></div>
<?php if ($thumblist != 'style3') { ?>  
<div class="caption">
<h4 class="<?php echo $titlepos; ?>"><a href="<?php echo $gallalbum['href']; ?>"><?php echo $gallalbum['name']; ?></a></h4>
<?php if ($descstat == 1) { ?>
<p><?php echo $gallalbum['description']; ?></p>
<?php } ?>
</div>
<?php } else { ?>
<?php if ($descstat == 1) { ?>
<div class="caption">    
<p><?php echo $gallalbum['description']; ?></p>
</div>    
<?php } ?> 
<?php } ?>    
</div>
</div>
<?php } ?>
</div>    
</div>
<?php } else { ?>
<div class="list-group box-content">
<div class="list-group">
<ul class="box-category">   
<?php foreach ($gallalbums as $gallalbum) { ?>
<li><a href="<?php echo $gallalbum['href']; ?>" class="list-group-item"><?php echo $gallalbum['name']; ?></a></li>
<?php } ?>
</ul>    
</div>
</div>    
<?php } ?>
</div>    
<script type="text/javascript"><!--
$(function() {
	$('.alb{{ module }}').matchHeight();
});
//--></script>	
