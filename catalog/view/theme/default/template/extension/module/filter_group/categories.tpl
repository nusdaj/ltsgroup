<?php if($catalogue) { ?>
	<a href="image/<?= $catalogue; ?>" class="product-catalogue" download><span>Download Catalogue</span>
	<img src="image/catalog/slicing/product/icon_prod-download.png" alt="Download"></a>
<?php } ?>
<hr class="solidhr">
<?php if($categories) { ?>
	<div id="side-categories">
		<div class="list-group-item item-header">Category</div>
		<div class="list-group-item">
			<?php foreach($categories as $category){ ?>
				
				<div class="side-categories-level-1">
					<div class="group">
						<div class="item level-1 <?= $category['active']; ?> product-hover" data-path="<?= $category['path']; ?>" >
							<a href="<?= $category['href']; ?>"><?= $category['name']; ?> <!-- AJ Apr 8: begin -->(<?= $category['total']  ?>)<!-- AJ Apr 8: end --></a>
							<?php if($category['child']){ ?>
							<div class="toggle level-1 pointer product-tog"><i class='fa fa-caret-down'></i></div>
							<?php } ?>
						</div>
					
						<?php if($category['child']){ ?>
							<div class="sub level-2">
									<?php foreach($category['child'] as $child_1){ ?>
										<div class="group" >
											<div class="item level-2 <?= $child_1['active']; ?>" data-path="<?= $child_1['path']; ?>" >
												<a href="<?= $child_1['href']; ?>" class="level-2-name"><?= $child_1['name']; ?><!-- AJ Apr 8: begin --> (<?= $child_1['total']  ?>)<!-- AJ Apr 8: end --></a>
												<?php if($child_1['child']){ ?>
													<div class="toggle level-2 pointer"><div class="caret"></div></div>
												<?php } ?>
											</div>
										
										<?php if($child_1['child']){ ?>
										<div class="sub level-3">
											<?php foreach($child_1['child'] as $child_2){ ?>
												<div class="item level-3" data-path="<?= $child_2['path']; ?>" >
													<a href="<?= $child_2['href']; ?>" class="<?= $child_2['active']; ?>" ><?= $child_2['name']; ?><!-- AJ Apr 8: begin --> (<?= $child_2['total']  ?>)<!-- AJ Apr 8: end --></a>
												</div>
											<?php } ?>
										</div>
										<?php } ?>
										<!---->

										</div>
									<?php } ?>
							</div>
						<?php } ?>
						<!---->
					</div>
				</div>
					
			<?php } ?>
		</div>
	</div>
<?php } ?>