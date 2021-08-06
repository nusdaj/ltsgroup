// Resize reCAPTCHA to fit width of container
// Since it has a fixed width, we're scaling
// using CSS3 transforms
// ------------------------------------------
// captchaScale = containerWidth / elementWidth

function scaleCaptcha(elementWidth) {
    // Width of the reCAPTCHA element, in pixels
    var reCaptchaWidth = 304;
    // Get the containing element's width
        var containerWidth = jQuery('#google_recaptcha').width();

    // Only scale the reCAPTCHA if it won't fit
    // inside the container
    if(reCaptchaWidth > containerWidth) {
        // Calculate the scale
        var captchaScale = containerWidth / reCaptchaWidth;
        // Apply the transformation
        jQuery('.g-recaptcha').css({
        'transform':'scale('+captchaScale+')'
        });
    }
}

jQuery(function() { 

    // Initialize scaling
    jQuery(window).load(function(){
        scaleCaptcha();
    });

    // Update scaling on window resize
    // Uses jQuery throttle plugin to limit strain on the browser
    jQuery(window).resize( jQuery.throttle( 100, scaleCaptcha ) );

});

/*
* jQuery throttle / debounce - v1.1 - 3/7/2010
* http://benalman.com/projects/jquery-throttle-debounce-plugin/
* 
* Copyright (c) 2010 "Cowboy" Ben Alman
* Dual licensed under the MIT and GPL licenses.
* http://benalman.com/about/license/
*/
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);  



var loading = {
	timeout_period : 300000,
	timeoutObj : null,
	start : function(msg) {
		$( "body" ).addClass ( "loading" );
		if(this.timeoutObj){
			clearTimeout(this.timeoutObj);
			this.timeoutObj = null;
		}
		this.timeoutObj = setTimeout(function () {
		alert("This action took too long to respond. You may want to refresh the page and try again.");
		}, this.timeout_period);
	},

	end : function() {
		clearTimeout ( this.timeoutObj );
		this.timeoutObj = null;
		$( "body" ).removeClass ( "loading" );
	}
};

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {
	$('.dropdown-submenu a[href=\'#\']').on('click', function(e){
		e.preventDefault();
	});

	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});

	/* Search */
	var url = $('base').attr('href');
	$('.search-custom button').on('click', function(){
		var value = $(this).prev().val();
		if ($('body').hasClass('short_hand')) {
			url += 'search';
		}
		else {
			url += 'index.php?route=product/search';
		}

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('.search-custom input[name=\'search\']').on('keydown', function (e) {
		if (e.keyCode == 13) {
			$(this).next().trigger('click');
		}
	});

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('.desktop [data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('.desktop [data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});

});

// Cart add remove functions
var clicked = 0;
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
				fbq('track', 'AddToCart');	
			},
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					// comment below to prevent issue like footer alignment gone when adding product to cart in product listing
				//	$('body').removeAttr('style');
					setTimeout(function(){
						location = json['redirect'];
					}, 1000);
				}

				if (json['success']) {				
					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart-quantity-total').text(json['total_quantity']);
						$('#cart-total').text(json['total']);
					}, 100);

				// 	swal({
				// 		title: json['success_title'],
				// 		html: json['success'],
				// 		type: "success"
				// 	});

					$('#cart > ul').load('index.php?route=common/cart/info ul > *');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart-quantity-total').text(json['total_quantity']);
					$('#cart-total').text(json['total']);
				}, 100);

				if ($('body.short_hand').length ){
					if (location.toString().indexOf('cart') > 1 || location.toString().indexOf('checkout') > 1 ){
						location.reload();
					}
					else{
						$('#cart > ul').load('index.php?route=common/cart/info ul > *');

				// 		swal({
				// 			title: json['success_remove_title'],
				// 			html: json['success'],
				// 			type: "success"
				// 		});
					}
				}
				else{
					if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout' || getURLVar('route') == 'quickcheckout/checkout') {
						location = 'index.php?route=checkout/cart';
					} else {
						$('#cart > ul').load('index.php?route=common/cart/info ul > *');

				// 		swal({
				// 			title: json['success_remove_title'],
				// 			html: json['success'],
				// 			type: "success"
				// 		});
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul > *');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					// $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				// 	swal({
				// 		title: json['success_title'],
				// 		html: json['success'],
				// 		type: "success"
				// 	});
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);
				
				fbq('track', 'AddToWishlist');

				//$('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					// $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

				// 	swal({
				// 		title: json['success_title'],
				// 		html: json['success'],
				// 		type: "success"
				// 	});

					//$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);

// product change image when hover - product/category
$(document).ready(function() {
	$('.product-block')
	  .mouseout(function() {
	  	if ($(this).find('.img2').length > 0) {
		    $(this).find('.img2').hide();
		    $(this).find('.img1').show();
	    }
	  })
	  .mouseover(function() {
	  	if ($(this).find('.img2').length > 0) {
		    $(this).find('.img1').hide();
		    $(this).find('.img2').show();
		}
	  });
});
// product change image when hover - product/category

// options in product list
$(document).ready(function() {
	$('.btn-cart').on('click', function() {
		var product_id = $(this).data('product-id');
		if($('#input-quantity-' + product_id).val() > 0) {
			$.ajax({
				url: 'index.php?route=checkout/cart/add',
				type: 'post',
				data: $('#product-' + product_id + ' input[type=\'text\'], #product-' + product_id + ' input[type=\'hidden\'], #product-' + product_id + ' input[type=\'radio\']:checked, #product-' + product_id + ' input[type=\'checkbox\']:checked, #product-' + product_id + ' select, #product-' + product_id + ' textarea'),
				dataType: 'json',
				success: function (json) {

					$('.alert, .text-danger').remove();
					$('.form-group').removeClass('has-error');

					if (json['redirect']) {
						// comment below to prevent issue like footer alignment gone when adding product to cart in product listing
					//	$('body').removeAttr('style');
						setTimeout(function(){
							location = json['redirect'];
						}, 1000);
					}


					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								var element = $('#input-option' + i.replace('_', '-'));

								if (element.parent().hasClass('input-group')) {
									element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								} else {
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
						}

						if (json['error']['recurring']) {
							$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
						}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// 		swal({
				// 			title: json['success_title'],
				// 			html: json['success'],
				// 			type: "success"
				// 		});

						setTimeout(function () {
							$('#cart-quantity-total').text(json['total_quantity']);
							$('#cart-total').text(json['total']);
						}, 100);

						$('#cart > ul').load('index.php?route=common/cart/info ul > *');
					}

					if(json['error_stock_add']){
						swal({
							title: json['error_stock_add_title'],
							html: json['error_stock_add'],
							type: "error"
						});
					}

					if(json['error_outofstock']){
						swal({
							title: json['error_outofstock_title'],
							html: json['error_outofstock'],
							type: "error"
						});
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
	$('.btn-enquiry').on('click', function () {
		var product_id = $(this).data('product-id');
		if($('#input-quantity-' + product_id).val() > 0) {
			$.ajax({
				url: 'index.php?route=enquiry/cart/add',
				type: 'post',
				data: $('#product-' + product_id + ' input[type=\'text\'], #product-' + product_id + ' input[type=\'hidden\'], #product-' + product_id + ' input[type=\'radio\']:checked, #product-' + product_id + ' input[type=\'checkbox\']:checked, #product-' + product_id + ' select, #product-' + product_id + ' textarea'),
				dataType: 'json',
				beforeSend: function () {
					$('.btn-enquiry').button('loading');
				},
				complete: function () {
					$('.btn-enquiry').button('reset');
				},
				success: function (json) {
					$('.alert, .text-danger').remove();
					$('.form-group').removeClass('has-error');

					if (json['redirect']) {
						// comment below to prevent issue like footer alignment gone when adding product to cart in product listing
						// $('body').removeAttr('style');
						setTimeout(function(){
							location = json['redirect'];
						}, 1000);
					}

					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								var element = $('#input-option' + i.replace('_', '-'));

								if (element.parent().hasClass('input-group')) {
									element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								} else {
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
						}

						if (json['error']['recurring']) {
							$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
						}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// 		swal({
				// 			title: json['success_title'],
				// 			html: json['success'],
				// 			type: "success"
				// 		});

						setTimeout(function () {
							$('#enquiry-quantity-total').text(json['total_quantity']);
							$('#enquiry-total').text(json['total']);
						}, 100);

						$('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
});
// options in product list
