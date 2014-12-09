var category_id = 0;
var platform_id = 0;
var sort_type = 'add_date';
var page = 0;
var productsDiv;
var cartProductsDiv;
var platformsEquivalent = {'Mac':'apple', 'Windows':'windows', 'Android': 'android', 'Linux': 'linux'};
// Extend the default Number object with a formatMoney() method:
// usage: someVar.formatMoney(decimalPlaces, symbol, thousandsSeparator, decimalSeparator)
// defaults: (2, "$", ",", ".")
Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "€";
	thousand = thousand || " ";
	decimal = decimal || ".";
	var number = this, 
	    negative = number < 0 ? "-" : "",
	    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
	    j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};

function getProducts()
{
	productsDiv.empty();
	$('#waiting_spinner').show();
	$.ajax(baseURL+"ajax/products/"+sort_type+"/"+category_id+"/"+platform_id+"/"+page).done(function(result){
		var products = result['products'];
		for (var i = 0; i < products.length; i++) {
			var product = products[i];
			var incart = (product['id'] in cart);
			var elem = '<a href="'+baseURL+'product/'+product['id']+'" title="'+product['name']+'"><div class="product">';
			elem += '<img src="'+baseURL+'assets/images/products/'+product['image']+'" />';
			elem += '<p>'+product['name']+'</p>';
			elem += '<div class="platforms">';
			var platforms = product['platforms'];
			for (var j = 0; j < platforms.length; j++)
			{
				elem += '<span class="fa fa-'+platformsEquivalent[platforms[j]['name']]+'"></span>';
			}
			elem += '</div>';
			elem += '<div data="'+product['id']+'" class="add-to-cart'+(incart?' selected':'')+'"><p class="add-to-cart-text">'+(incart?checkoutText:addToCartText)+'</p>';
			elem += '<p class="price">'+parseFloat(product['price']).formatMoney()+'</p></div>';
			elem += '</div></a>';
			productsDiv.append(elem);
		}
		$('#waiting_spinner').hide();
		$('.add-to-cart').on('click', function(e){
			e.preventDefault();
			addToCart(this, e);
		});
	});
}
function OpenCart()
{
	$('#cart-content').css('right', '0px');
	$('#grey-veil').fadeIn(500);
}
function CloseCart()
{
	$('#cart-content').css('right', '-300px');
	$('#grey-veil').fadeOut(500);
}
function addToCart(elem, event)
{
	var id = $(elem).attr('data');
	if(!$(elem).hasClass('selected'))
	{
		$(elem).addClass('selected');
		$('.add-to-cart-text',$(elem)).html(checkoutText);
		$.ajax(baseURL+'ajax/addtocart/'+id).done(function(result){
			if(result)
			{
				$.ajax(baseURL+'ajax/getProduct/'+id).done(function(product){
					if(product)
					{
						cart[id] = product;
						updateCart();
						$("div.main").append('<img src="'+baseURL+'assets/images/products/'+product['image']+'" class="tocart" style="right: '+($(window).width() - event.clientX)+'px; top: '+event.clientY+'px;"/>');
						$('.tocart').bind('animationend', function(e){
							$(this).remove();
						});
			
					}
				});
			}
		});
	}
	else
	{
		OpenCart();
	}
}
function removeFromCart(elem)
{
	var id = $(elem).attr('data');
	var productElem = $('.add-to-cart[data="'+id+'"]');
	if($(productElem).hasClass('selected'))
	{
		$(productElem).removeClass('selected');
		$('.add-to-cart-text',$(productElem)).html(addToCartText);
	}
	$.ajax(baseURL+'ajax/removefromcart/'+id).done(function(result){
		if(result)
		{
			delete cart[id];
			updateCart();
		}
	});
	$(elem).parent().remove();
}
function updateCart()
{
	$(".in-cart-count").html(Object.keys(cart).length);
	if(Object.keys(cart).length > 1)
	{
		$(".cart-s").html('s');
	}
	else
	{
		$(".cart-s").html('');
	}
	var total = 0;
	cartProductsDiv.empty();
	for(var productId in cart)
	{
		var newElem = '<div class="product-in-cart">';
		newElem += '<span class="remove-from-cart glyphicon glyphicon-remove-circle" data="'+productId+'"></span>';
		newElem += '<div><p>'+cart[productId]['name']+'</p></div>';
		newElem += '<div><p>'+parseFloat(cart[productId]['price']).formatMoney()+'</p></div>';
		newElem += '</div>';
		cartProductsDiv.append(newElem);

		$('.remove-from-cart', cartProductsDiv).on('click', function(e){
			e.preventDefault();
			removeFromCart(this);
		});
		total += parseFloat(cart[productId]['price']);
	}
	$('#total').html(total.formatMoney());
}
$(function()
{
	$('#waiting_spinner').hide();
	productsDiv = $('#products');
	cartProductsDiv = $("#products-in-cart");
	if(productsDiv.length)
	{
		getProducts();
	}
	$("#categories>ul>li").on('click', function(e){
		if(category_id != $(this).attr("data"))
		{
			$("#categories>ul>li.selected").removeClass("selected");
			$(this).addClass("selected");
			category_id = $(this).attr("data");
			getProducts();
		}
	});
	$("#platforms>ul>li").on('click', function(e){
		if(platform_id != $(this).attr("data"))
		{
			$("#platforms>ul>li.selected").removeClass("selected");
			$(this).addClass("selected");
			platform_id = $(this).attr("data");
			getProducts();
		}
	});
	$("#sort_types>ul>li").on('click', function(e){
		if(sort_type != $(this).attr("data"))
		{
			sort_type = $(this).attr("data");
			$("#sort_types>ul>li.selected").removeClass("selected");
			$(this).addClass("selected");
			getProducts();
		}
	});
	$(".shopping-cart>h1").on('click', function(e)
	{
		OpenCart();
	});
	$("#grey-veil, .close-cart").on('click', function(e)
	{
		CloseCart();
	});
	$('.add-to-cart').on('click', function(e){
		e.preventDefault();
		addToCart(this, e);
	});
	console.log(cart);
	updateCart();
});