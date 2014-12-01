		<div class="main">
			<form action="" class="form-horizontal" method="post">
				<div class="jumbotron">
					<div class="container">
						<h1>Formulaire d'inscription</h1>
					</div>
				</div>
				<fieldset class="container">
					<fieldset name ="coordinates">
						<legend>Mes coordonnées</legend>
						<?php if(isset($error)): ?>
							<p class="error"><?=$error;?></p>
						<?php endif ?>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="name">Nom</label>
							<div class="col-sm-9">
								<input required class="form-control" name="name" id="name" type="text" value="<?= isset($_POST['name'])?$_POST['name']:''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="firstName">Prénom</label>
							<div class="col-sm-9">
								<input required class="form-control" name="firstName" id="firstName" type="text" value="<?= isset($_POST['firstName'])?$_POST['firstName']:'';?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="email">Email</label>
							<div class="col-sm-9">
								<div class="input-group">
									<div class="input-group-addon">@</div>
									<input required class="form-control" name="email" id="email" type="text" value="<?= isset($_POST['email'])?$_POST['email']:'';?>"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="phone_number">N° de téléphone</label>
							<div class="col-sm-9">
								<input required class="form-control" name="phone_number" id="phone_number" type="text" value="<?= isset($_POST['phone_number'])?$_POST['phone_number']:'';?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="password">Mot de passe</label>
							<div class="col-sm-9">
								<input required class="form-control" name="password" id="password" type="password"/>
							</div>
						</div>
						<input type="hidden" name="referer" value="<?=$referer ?>"/>
					</fieldset>
				</fieldset>
				<div class="jumbotron">
					<div class="container">
						<button class="btn btn-primary" type="submit">Envoyer</button>
						<button class="btn btn-primary" id="previousPage" type="button">Retour</button>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>