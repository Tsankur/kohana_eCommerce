<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<title><?=$title; ?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/header.css">
		<?php foreach($styles as $style): ?>
			<link rel="stylesheet" type="text/css" href="<?=URL::base()?>assets/css/<?=$style;?>.css">
		<?php endforeach;?>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>
		<?php if(isset($referer)): ?>
			var referer = "<?= $referer; ?>";
		<?php endif ?>
		var baseURL = <?= URL::base(); ?>;
		$(function(){
			<?php if(isset($referer)): ?>
				$("#previousPage").on('click', function(){
					window.location = referer;
				});
			<?php endif ?>
			$("#registerButton").on('click', function(){
				window.location = "<?=URL::base();?>user/register";
			});
			$("#logoutButton").on('click', function(){
				window.location = "<?=URL::base();?>user/logout";
			});
			$("#myProductsButton").on('click', function(){
				window.location = "<?=URL::base();?>products/myproducts";
			});
			$(".language").on('click', function(){
				window.location = "<?=URL::base();?>user/changelanguage?lang="+$(this).attr('data');
			});
		});
		</script>
		<?php foreach($scripts as $script): ?>
			<script src="<?=URL::base()?>assets/js/<?=$script;?>.js"></script>
		<?php endforeach;?>
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
		<header>
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?=URL::base();?>">Game Online Shop</a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						
						<div class="languages navbar-right">
							<?php if(I18n::lang() != 'fr-fr'): ?>
								<img class="language" data="fr-fr" src="<?= URL::base();?>assets/images/flags/fr.png" title="French">
							<?php endif; ?>
							<?php if(I18n::lang() != 'en-us'): ?>
								<img class="language" data="en-us" src="<?= URL::base();?>assets/images/flags/us.png" title="English">
							<?php endif; ?>
						</div>
						<?php if(!isset($_SESSION['name'])): ?>
							<form class="navbar-form navbar-right" role="form" action="<?= URL::base();?>user/login" method="POST">
								<div class="form-group">
									<input class="form-control" name="email" type="text" placeholder="<?=__('Email');?>"></input>
								</div>
								<div class="form-group">
									<input class="form-control" name="password" type="password" placeholder="<?=__('Password');?>"></input>
								</div>
								<button class="btn btn-success" type="submit"><?=__('Sign in');?></button>
								<button class="btn btn-success" id="registerButton" type="button"><?=__('Register');?></button>
							</form>
						<?php else: ?>
							<div class="navbar-form navbar-right" >
								<button class="btn btn-success" id="myProductsButton" type="button"><?=__('My games');?></button>
								<button class="btn btn-danger" id="logoutButton" type="button"><?=__('Logout');?></button>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</nav>
		</header>