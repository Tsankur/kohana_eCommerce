		<div class="main">
			<div class='container'>
				<script>
					var addToCartText = "<?= __('Add to cart'); ?>";
					var cart = <?= json_encode($cart);?>;
					var checkoutText = "<?= __('Checkout'); ?>";
				</script>
				<div id="product" class="col-sm-12">
					<div class="producthead col-sm-12">
						<img src="<?= URL::base()?>assets/images/products/<?= $product['image']?>" />
						<div class="basicInfo">
							<p><?= $product['name']?></p>
							<div data="<?=$product['id']?>" class="add-to-cart<?= array_key_exists($product['id'], $cart)?' selected':'';?>">
								<p class="add-to-cart-text"><?= array_key_exists($product['id'], $cart)?__('Checkout'):__('Add to cart'); ?></p>
								<p class="price">€<?=number_format($product['price'], 2)?></p>
							</div>
						</div>
					</div>
					<div class="productbody col-sm-12">
						<div class="col-sm-9">
							<h3><?= __('Description')?> :</h3>
							<p><?= $product['description'] ?></p>
						</div>
						<aside class="col-sm-3">
							<div class="platforms">
							<h3><?= __('Platforms')?> :</h3>
								<?php foreach ($product['platforms'] as $plateform):?>
								<span class="fa fa-<?=$platformsEquivalent[$plateform['name']]?>"></span>
								<?php endforeach; ?>
							</div>
							<div class="categories">
								<h3><?= __('Categories')?> :</h3>
								<ul>
									<?php foreach ($product['categories'] as $category):?>
									<li><?=$category['name']?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</aside>
					</div>
				</div>
			</div>
			<?php echo View::Factory('cart'); ?>
		</div>
	</body>
</html>