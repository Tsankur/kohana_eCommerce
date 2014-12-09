		<div class="main">
			<form action="" class="form-horizontal" method="post">
				<div class="jumbotron">
					<div class="container">
						<h1><?= __('Register form');?></h1>
					</div>
				</div>
				<fieldset class="container">
					<fieldset name ="coordinates">
						<legend><?= __('My coordinates');?></legend>
						<?php if(isset($error)): ?>
							<p class="error"><?=$error;?></p>
						<?php endif ?>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="name"><?=__('Name') ?></label>
							<div class="col-sm-9">
								<input required class="form-control" name="name" id="name" type="text" value="<?= isset($_POST['name'])?$_POST['name']:''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="firstName"><?=__('Firstname') ?></label>
							<div class="col-sm-9">
								<input required class="form-control" name="firstName" id="firstName" type="text" value="<?= isset($_POST['firstName'])?$_POST['firstName']:'';?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="email"><?=__('Email') ?></label>
							<div class="col-sm-9">
								<div class="input-group">
									<div class="input-group-addon">@</div>
									<input required class="form-control" name="email" id="email" type="text" value="<?= isset($_POST['email'])?$_POST['email']:'';?>"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="phone_number"><?=__('Phone number') ?></label>
							<div class="col-sm-9">
								<input required class="form-control" name="phone_number" id="phone_number" type="text" value="<?= isset($_POST['phone_number'])?$_POST['phone_number']:'';?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="password"><?=__('Password');?></label>
							<div class="col-sm-9">
								<input required class="form-control" name="password" id="password" type="password"/>
							</div>
						</div>
						<input type="hidden" name="referer" value="<?=$referer ?>"/>
					</fieldset>
				</fieldset>
				<div class="jumbotron">
					<div class="container">
						<button class="btn btn-primary" type="submit"><?=__('Send');?></button>
						<button class="btn btn-primary" id="previousPage" type="button"><?=__('Return');?></button>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>