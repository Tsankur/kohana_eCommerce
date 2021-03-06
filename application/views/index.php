		<div class="main">
			<div class='container'>
				<script>
					var addToCartText = "<?= __('Add to cart'); ?>";
					var checkoutText = "<?= __('Checkout'); ?>";
					var cart = <?= json_encode($cart);?>;
					var myproducts = "<?= $myproducts ?>";
					<?php if(isset($_SESSION['filter'])): ?>
						var filter = <?= json_encode($_SESSION['filter']);?>;
					<?php endif; ?>
				</script>
				<aside class="col-sm-3">
					<input id="search" type="text" placeholder="Search">
					<div id="sort_types">
						<p><?= __('SORT BY');?> :</p>
						<ul>
							<li data="add_date"><?= __('Newest');?></li>
							<li data="sells"><?= __('Bestselling');?></li>
							<li data="views"><?= __('Views');?></li>
							<li data="name"><?= __('Alphabetical');?></li>
						</ul>
					</div>
					<div id="platforms">
						<p><?= __('PLATFORMS:');?></p>
						<ul>
							<li data="0"><?= __('All');?></li>
							<?php  foreach ($platforms as $platform): ?>
							<li data="<?= $platform['id']?>"><?= __($platform['name']);?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div id="categories">
						<p><?= __('CATEGORIES:');?></p>
						<ul>
							<li data="0"><?= __('All');?></li>
							<?php  foreach ($categories as $category): ?>
							<li data="<?= $category['id']?>"><?= __($category['name']);?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<button class="btn btn-danger" id="reset-filter">Reset</button>
				</aside>
				<div id="waiting_spinner">
					<p>Loading</p>
					<span class="fa fa-spinner fa-spin"></span>
				</div>
				<div id="products" class="col-sm-9">
					
				</div>
			</div>
			<?php echo View::Factory('cart'); ?>
		</div>
	</body>
</html>