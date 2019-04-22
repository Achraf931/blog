<!DOCTYPE html>
<html lang="fr">
 <head>

	<title>Login - Mon premier blog !</title>

   <?php require 'partials/head_assets.php'; ?>

 </head>
 <body class="article-body">
	<div class="container-fluid">

		<?php require 'partials/header.php'; ?>

		<div class="row my-3 article-content">

			<?php require './controllers/controller_nav.php'; ?>

			<main class="col-9">

				<ul class="nav nav-tabs justify-content-center nav-fill" role="tablist">
					<li class="nav-item">
						<a class="nav-link <?= !isset($_POST['register']) ? 'active' : '' ;?>" data-toggle="tab" href="#login" role="tab">Connexion</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= isset($_POST['register']) ? 'active' : '' ;?>" data-toggle="tab" href="#register" role="tab">Inscription</a>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane container-fluid <?= !isset($_POST['register']) ? 'active' : '' ;?>" id="login" role="tabpanel">

						<form action="index.php?page=login-register" method="post" class="p-4 row flex-column">

							<h4 class="pb-4 col-sm-8 offset-sm-2">Connexion</h4>

							<?php if(isset($loginError)): ?>
							<div class="text-danger col-sm-8 offset-sm-2 mb-4"><?php echo $loginError; ?></div>
							<?php endif; ?>

							<div class="form-group col-sm-8 offset-sm-2">
								<label for="email">Email</label>
								<input class="form-control" value="<?= isset($loginError) ? $_POST['email'] : '' ?>" type="email" placeholder="Email" name="email" id="email" />
							</div>

							<div class="form-group col-sm-8 offset-sm-2">
								<label for="password">Mot de passe</label>
								<input class="form-control" value="" type="password" placeholder="Mot de passe" name="password" id="password" />
							</div>

							<div class="text-right col-sm-8 offset-sm-2">
								<input class="btn btn-success" type="submit" name="login" value="Valider" />
							</div>

						</form>

					</div>
					<div class="tab-pane container-fluid <?= isset($_POST['register']) ? 'active' : '' ;?>" id="register" role="tabpanel">

						<form action="index.php?page=login-register" method="post" class="p-4 row flex-column">

							<h4 class="pb-4 col-sm-8 offset-sm-2">Inscription</h4>

							<?php if(isset($registerError)): ?>
							<div class="text-danger col-sm-8 offset-sm-2 mb-4"><?php echo $registerError; ?></div>
							<?php endif; ?>

							<div class="form-group col-sm-8 offset-sm-2">
								<label for="firstname">Prénom <b class="text-danger">*</b></label>
								<input class="form-control" value="<?= isset($registerError) ? $_POST['firstname'] : '' ?>" type="text" placeholder="Prénom" name="firstname" id="firstname" />
							</div>
							<div class="form-group col-sm-8 offset-sm-2">
								<label for="lastname">Nom de famille</label>
								<input class="form-control" value="<?= isset($registerError) ? $_POST['lastname'] : '' ?>" type="text" placeholder="Nom de famille" name="lastname" id="lastname" />
							</div>
							<div class="form-group col-sm-8 offset-sm-2">
								<label for="email">Email <b class="text-danger">*</b></label>
								<input class="form-control" value="<?= isset($registerError) ? $_POST['email'] : '' ?>" type="email" placeholder="Email" name="email" id="email" />
							</div>
							<div class="form-group col-sm-8 offset-sm-2">
								<label for="password">Mot de passe <b class="text-danger">*</b></label>
								<input class="form-control" value="" type="password" placeholder="Mot de passe" name="password" id="password" />
							</div>
							<div class="form-group col-sm-8 offset-sm-2">
								<label for="password_confirm">Confirmation du mot de passe <b class="text-danger">*</b></label>
								<input class="form-control" value="" type="password" placeholder="Confirmation du mot de passe" name="password_confirm" id="password_confirm" />
							</div>
							<div class="form-group col-sm-8 offset-sm-2">
								<label for="bio">Biographie</label>
								<textarea class="form-control" name="bio" id="bio" placeholder="Ta vie Ton oeuvre..."><?= isset($registerError) ? $_POST['bio'] : '' ?></textarea>
							</div>

							<div class="text-right col-sm-8 offset-sm-2">
								<p class="text-danger">* champs requis</p>
								<input class="btn btn-success" type="submit" name="register" value="Valider" />
							</div>

						</form>

					</div>
				</div>
			</main>

		</div>

		<?php require 'partials/footer.php'; ?>

	</div>
 </body>
</html>
