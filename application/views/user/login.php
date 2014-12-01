<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<title>Connection</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/datepicker3.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		<script>
		var referer = "<?= $referer; ?>";
		//var baseURL = <?= URL::base(); ?>;
		$(function(){
			$("#previousPage").on('click', function(){
				window.location = referer;
			});
		});
		</script>
		<style>
			body
			{
				font-family: sans-serif;
			}
			.container
			{
				width: 900px;
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
					<h1>Connection</h1>
				</div>
			</div>
			<fieldset class="container">
				<fieldset name ="coordinates">
					<legend>Mes coordonnées</legend>
					<?php if(isset($error)): ?>
						<p class="error"><?=$error;?></p>
					<?php endif ?>
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
	</body>
</html>