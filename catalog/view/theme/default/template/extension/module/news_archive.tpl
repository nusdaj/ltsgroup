<button id="articles-filter-trigger-open" class="btn btn-primary" onclick="$('#articles-column-left').addClass('open');" ><?= $button_filter; ?></button>
<div id="articles-column-left" class="f16">
	<button id="articles-filter-trigger-close" class="btn btn-danger fixed position-right-top" onclick="$('#articles-column-left').removeClass('open');"> <i class="fa fa-times"></i> </button>
	<h3 class="news-archive">Archives</h3>
	<div class="list-group pd-b40">
		<?php $index = 0; ?>
				<?php foreach ($archives as $archive) { ?>
			<?php $index++ ?>
			<?php //if ($index > 1 && (count($archive['month']) > 3 || count($archive) > 4) && (count($archive) > 2 || count($archive['month']) > 5)) { ?>
				<div class="list-group" style="display: block;">
					<?php foreach ($archive['month'] as $month) { ?>
							<a class="list-group-item news-month <?= $archive_query == ($archive['year'].'-'.$month['num']) ? 'active' : '' ?>" href="<?php echo $month['href']; ?>"><?php echo $month['name']; ?></a>
					<?php } ?>
				</div>
			<?php /*} else { ?>
				<?php foreach ($archive['month'] as $month) { ?>
					<a href="<?php echo $month['href']; ?>" class="list-group-item"><?php echo $month['name']; ?></a>
				<?php } ?>
			<?php }*/ ?>
				<?php } ?>
			<?php echo !$archives ? 'No articles' : ''; ?>
	</div>
</div>