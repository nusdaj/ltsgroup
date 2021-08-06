<?php if($active){ ?>
	
	<div id="side-price">
		<div class="list-group-item item-header product-prices-lbl"><?= $heading_title; ?></div>
		<div class="list-group-item pricerange-container">
			<div class="price-container">
			
				<div class="input-group left-price">
					$<span id="minmin" class="minmin" value="<?= $price_min; ?>"><?= $price_min; ?></span>
					<input type="hidden" 
					name="price_min" 
					min="<?= $lowest_price; ?>" 
					max="<?= $highest_price; ?>" 
					class="form-control input-number price-range" 
					value="<?= $price_min; ?>" 
					onkeyup="updateSlider();"
					onkeyup="updateSlider();"
					id="price_min"
					disabled
					/>
				</div>
				<div class="input-group right-price">
					$<span id="maxmax" class="maxmax" value="<?= $price_max; ?>"><?= $price_max; ?></span>
					<input type="hidden" 
					name="price_max" 
					min="<?= $lowest_price; ?>" 
					max="<?= $highest_price; ?>" 
					class="form-control input-number price-range" 
					value="<?= $price_max; ?>"
					onkeyup="updateSlider();"
					onkeyup="updateSlider();"
					id="price_max"
					disabled
					/>
				</div>
			</div>
		
			<div class="slider-price-div"></div>
		</div>
		
		<script type='text/javascript' >
			$(".slider-price-div").slider({
				min: <?= $lowest_price; ?>,
				max: <?= $highest_price; ?>,
				step: 1.00,
				range: true,
				values: [<?= $price_min; ?>, <?= $price_max; ?>],
				create: function (event, ui) {
					$(".ui-slider-handle").attr("onclick", "");
				},
				slide: function () {
					val = $(this).slider("values");

					price_min = val[0].toFixed(2);
					price_max = val[1].toFixed(2);

					$("input[name='price_min']").val(price_min);
					$(".minmin").text(price_min);
					$("input[name='price_max']").val(price_max);
					$(".maxmax").text(price_max);
				},
				stop: function (event, ui) {
					val = $(this).slider("values");

					price_min = val[0].toFixed(2);
					price_max = val[1].toFixed(2);

					$("input[name='price_min']").val(price_min);
					$(".minmin").text(price_min);
					$("input[name='price_max']").val(price_max);
					$(".maxmax").text(price_max);

					applyFilter();
				}
			});

			function updateSlider(){

				let price_min = $(".price-range-min").val();
				let price_max = $(".price-range-max").val();

				$(".slider-price-div").slider( "values", [price_min, price_max]);
				
				if(price_min > -1 && price_max > -1){
					applyFilter();
				}
			}


		</script>
		
	</div>
	
<?php } ?>