			<div class="shopping-cart">
				<h1><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> <?=__('Cart');?> (<span class="in-cart-count"></span> <?=__('item')?><span class="cart-s"></span>)</h1>
			</div>
			<div id="grey-veil"></div>
			<div id="cart-content">
				<div>
					<span class="close-cart glyphicon glyphicon-remove-circle"></span>
					<h2><?=__('Shopping Cart'); ?></h2>
					<p><span class="in-cart-count"></span> <?=__('item');?><span class="cart-s"></span></p>
				</div>
				<div id="products-in-cart">
				</div>
				<div id="products-total">
					<p><?= __('Total');?></p>
					<p id="total"></p>
				</div>
				<div id="info-form" class="clearfix">
					<form>
						<div class=" col-sm-12">
							<input required type="text" class="form-control" name="firstname" id="firstname" placeholder="<?= __('Firstname')?>">
						</div>
						<div class=" col-sm-12">
							<input required type="text" class="form-control" name="name" id="name" placeholder="<?= __('Name')?>">
						</div>
						<div class=" col-sm-8">
							<input required type="text" class="form-control" name="card_number" id="card_number" placeholder="<?= __('Card number')?>">
						</div>
						<div class=" col-sm-4">
							<input required type="text" class="form-control" name="cvv" id="cvv" placeholder="<?= __('Vcc')?>">
						</div>
						<div class=" col-sm-7">
							<input required type="text" class="form-control" name="year" id="year" placeholder="<?= __('Year')?>">
						</div>
						<div class=" col-sm-5">
							<input required type="text" class="form-control" name="month" id="month" placeholder="<?= __('Month')?>">
						</div>
					</form>
				</div>
				<div id="checkout">
					<p><?= __('Checkout');?></p>
				</div>
				<div id="payment_waiting_spinner">
					<p>Processing</p>
					<span class="fa fa-spinner fa-spin"></span>
				</div>
				<p id="cartError"></p>
			</div>