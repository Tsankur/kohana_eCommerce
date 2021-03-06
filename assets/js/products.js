var category_id = 0;
var platform_id = 0;
var sort_type = 'add_date';
var page = 0;
var productsDiv;
var cartProductsDiv;
var searchInput;
var platformsEquivalent = {'Mac':'apple', 'Windows':'windows', 'Android': 'android', 'Linux': 'linux'};
var searchTimeout = null;
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
	$.ajax(baseURL+(myproducts?"ajax/myproducts/":"ajax/products/")+sort_type+"/"+category_id+"/"+platform_id+"/"+page+"/"+searchInput.val()).done(function(result){
		var pageCount = result['pageCount'];
		if(page >= pageCount)
		{
			page = Math.ceil(pageCount)-1;
			getProducts();
		}
		else
		{
			var paginationElem = '<div class="page-links">';
			if(page <= 0)
			{
				paginationElem += '<span class="page-link" disabled>« Précédent</span>';
			}
			else
			{
				paginationElem += '<a class="page-link" data="'+(page)+'">« Précédent</a>';
			}
			paginationElem += "<div>";
			if(pageCount > 10)
			{
				if(page > 4)
				{
					paginationElem += '<a class="page-link" data="1">1</a>';
				}
				if(page > 5)
				{
					paginationElem += '<span>...</span>';
				}
			}
			var begin = (pageCount > 10)?(Math.min(Math.max(0, page-4), pageCount-10)):0;
			var end = (pageCount > 10)?(Math.max(Math.min(pageCount, page+5), 10)):pageCount;
			for (var i=begin; i < end; i++)
			{
				if(i == page)
				{
					paginationElem += '<span class="page-link selected">'+(i+1)+'</span>';
				}
				else
				{
					paginationElem += '<a class="page-link" data="'+(i+1)+'">'+(i+1)+'</a>';
				}
			}
			if(pageCount > 10)
			{
				if(page < pageCount-6)
				{
					paginationElem += '<span>...</span>';
				}
				if(page < pageCount-5)
				{
					paginationElem += '<a class="page-link" data="'+pageCount+'">'+pageCount+'</a>';
				}
			}
			paginationElem += "</div>";
			if(page >= pageCount - 1)
			{
				paginationElem += '<span class="page-link" disabled>Suivant »</span>';
			}
			else
			{
				paginationElem += '<a class="page-link" data="'+(page+2)+'">Suivant »</a>';
			}
			paginationElem += "</div>"
			productsDiv.append(paginationElem);
			if(pageCount == 0)
			{
				productsDiv.append("<div><p>Aucun produit ne correspond a votre recherche</p></div>");
			}
			var products = result['products'];
			for (var i = 0; i < products.length; i++)
			{
				var product = products[i];
				var incart = (product['id'] in cart);
				var elem = '<a href="'+baseURL+'product/'+product['id']+'" title="'+product['name']+'"><div class="product">';
				elem += '<div><img src="'+baseURL+'assets/images/products/'+product['image']+'" />';
				elem += '<p>'+product['name']+'</p></div>';
				elem += '<div><div class="platforms">';
				var platforms = product['platforms'];
				for (var j = 0; j < platforms.length; j++)
				{
					elem += '<span class="fa fa-'+platformsEquivalent[platforms[j]['name']]+'"></span>';
				}
				elem += '</div>';
				if(!myproducts)
				{
					elem += '<div data="'+product['id']+'" class="add-to-cart'+(incart?' selected':'')+'"><p class="add-to-cart-text">'+(incart?checkoutText:addToCartText)+'</p>';
					elem += '<p class="price">'+parseFloat(product['price']).formatMoney()+'</p></div>';
				}
				elem += '</div></div></a>';
				productsDiv.append(elem);
			}

			productsDiv.append(paginationElem);

			$(".page-link[data]").on('click', function(e){
				if(page != $(this).attr("data") - 1)
				{
					page = $(this).attr("data") - 1;
					getProducts();
				}
			});

			$('#waiting_spinner').hide();
			$('.add-to-cart').on('click', function(e){
				e.preventDefault();
				addToCart(this, e);
			});
		}
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
	$("#cartError").hide();
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
	if(Object.keys(cart).length > 0)
	{
		$("#info-form").show();
	}
	else
	{
		$("#info-form").hide();
	}
	var total = 0;
	cartProductsDiv.empty();
	for(var productId in cart)
	{
		var newElem = '<div class="product-in-cart">';
		newElem += '<span class="remove-from-cart glyphicon glyphicon-remove-circle" data="'+productId+'"></span>';
		newElem += '<div><p title="'+cart[productId]['name']+'">'+cart[productId]['name']+'</p></div>';
		newElem += '<div><p>'+parseFloat(cart[productId]['price']).formatMoney()+'</p></div>';
		newElem += '</div>';
		cartProductsDiv.append(newElem);

		total += parseFloat(cart[productId]['price']);
	}
	$('.remove-from-cart', cartProductsDiv).on('click', function(e){
		e.preventDefault();
		removeFromCart(this);
	});
	$('#total').html(total.formatMoney());
}
function checkoutPayment()
{
	$('#payment_waiting_spinner').show();
	$.ajax(baseURL+"ajax/paycart", {method: 'post', data: {'card_type': 'visa',
		'firstname': $("#firstname").val(),
		'name': $("#name").val(),
		'card_number': $("#card_number").val(),
		'cvv': $("#cvv").val(),
		'year': $("#year").val(),
		'month': $("#month").val()}}).done(function(result){
		$('#cartError').show();
		if(result["status"] == "error")
		{
			$("#cartError").html(result['error'])
		}
		else if(result["status"] == "success")
		{
			$("#cartError").html(result['message'])
			$("#info-form>form")[0].reset();
			for (var i = 0; i < result['sells'].length; i++) {
				var productElem = $('.add-to-cart[data="'+result['sells'][i]+'"]');
				if($(productElem).hasClass('selected'))
				{
					$(productElem).removeClass('selected');
					$('.add-to-cart-text',$(productElem)).html(addToCartText);
				}
				delete cart[result['sells'][i]]
			};
		}
		$('#payment_waiting_spinner').hide();
		updateCart();
	});
}
$(function()
{
	searchInput = $("#search");
	if(filter != undefined)
	{
		sort_type = filter[0];
		category_id = filter[1];
		platform_id = filter[2];
		page = filter[3];
		searchInput.val(filter[4])
	}
	$("#categories>ul>li[data="+category_id+"]").addClass("selected");
	$("#platforms>ul>li[data="+platform_id+"]").addClass("selected");
	$("#sort_types>ul>li[data="+sort_type+"]").addClass("selected");
	console.log(filter);
	$("#info-form").hide();
	$('#waiting_spinner').hide();
	$('#payment_waiting_spinner').hide();
	$('#cartError').hide();
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
	$("#checkout").on('click', function(e)
	{
		checkoutPayment();
	});
	$("#reset-filter").on('click', function(e)
	{
		category_id = 0;
		platform_id = 0;
		sort_type = "add_date";
		$("#sort_types>ul>li.selected").removeClass("selected");
		$("#categories>ul>li.selected").removeClass("selected");
		$("#platforms>ul>li.selected").removeClass("selected");
		searchInput.val('');
		$("#categories>ul>li[data="+category_id+"]").addClass("selected");
		$("#platforms>ul>li[data="+platform_id+"]").addClass("selected");
		$("#sort_types>ul>li[data="+sort_type+"]").addClass("selected");
		page = 0;
		getProducts();
	});
	searchInput.on('keyup', function(){
		if(searchTimeout)
		{
			clearTimeout(searchTimeout);
		}
		searchTimeout = setTimeout(getProducts, 1000);
	});
	console.log(cart);
	updateCart();
});