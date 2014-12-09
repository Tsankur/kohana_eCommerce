<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<title>Admin login</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/bootstrap.min.css">
		<style>
			body
			{
				font-family: sans-serif;
				padding-top: 51px;
			}
			.error
			{
				color: red;
			}
		</style>
	</head>
	<body>
		<form action="" class="form-horizontal" method="post">
			<div class="jumbotron">
				<div class="container">
					<h1>Admin login</h1>
				</div>
			</div>
			<fieldset class="container">
				<fieldset name ="coordinates">
					<legend>Info de connexion</legend>
					<?php if(isset($error)): ?>
						<p class="error"><?=$error;?></p>
					<?php endif ?>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="login">Login</label>
						<div class="col-sm-9">
							<input required class="form-control" name="login" id="login" type="text" value="<?= isset($_POST['login'])?$_POST['login']:'';?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="password">Mot de passe</label>
						<div class="col-sm-9">
							<input required class="form-control" name="password" id="password" type="password"/>
						</div>
					</div>
				</fieldset>
			</fieldset>
			<div class="jumbotron">
				<div class="container">
					<button class="btn btn-primary" type="submit">Envoyer</button>
				</div>
			</div>
		</form>
	</body>
</html>