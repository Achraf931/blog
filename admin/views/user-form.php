<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Administration des utilisateurs - Mon premier blog !</title>
		<?php require 'partials/head_assets.php'; ?>
	</head>
	<body class="index-body">
		<div class="container-fluid">
			<?php require 'partials/header.php'; ?>
			<div class="row my-3 index-content">
				<?php require 'partials/nav.php'; ?>
				<section class="col-9">
					<header class="pb-3">
						<!-- Si $user existe, on affiche "Modifier" SINON on affiche "Ajouter" -->
						<h4><?php if(isset($user)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> un utilisateur</h4>
					</header>
					<?php if(isset($message)): //si un message a été généré plus haut, l'afficher ?>
					<div class="bg-danger text-white">
						<?= $message; ?>
					</div>
					<?php endif; ?>
					<!-- Si $user existe, chaque champ du formulaire sera pré-remplit avec les informations de l'utilisateur -->
					<form action="user-form.php" method="post">
						<div class="form-group">
							<label for="firstname">Prénom :</label>
							<input class="form-control" value="<?= isset($user) ? htmlentities($user['firstname']) : '';?>" type="text" placeholder="Prénom" name="firstname" id="firstname" />
						</div>
						<div class="form-group">
							<label for="lastname">Nom de famille : </label>
							<input class="form-control" value="<?= isset($user) ? htmlentities($user['lastname']) : '';?>" type="text" placeholder="Nom de famille" name="lastname" id="lastname" />
						</div>
						<div class="form-group">
							<label for="email">Email :</label>
							<input class="form-control" value="<?= isset($user) ? htmlentities($user['email']) : '';?>" type="email" placeholder="Email" name="email" id="email" />
						</div>
						<div class="form-group">
							<label for="password">Password <?= isset($user) ? '(uniquement si vous souhaitez modifier le mot de passe actuel)' : '';?>: </label>
							<input class="form-control" type="password" placeholder="Mot de passe" name="password" id="password" />
						</div>
						<div class="form-group">
							<label for="bio">Biographie :</label>
							<textarea class="form-control" name="bio" id="bio" placeholder="Sa vie son oeuvre..."><?= isset($user) ? htmlentities($user['bio']) : '';?></textarea>
						</div>
						<div class="form-group">
							<label for="is_admin"> Admin ?</label>
							<select class="form-control" name="is_admin" id="is_admin">
								<option value="0" <?= isset($user) && $user['is_admin'] == 0 ? 'selected' : '';?>>Non</option>
								<option value="1" <?= isset($user) && $user['is_admin'] == 1 ? 'selected' : '';?>>Oui</option>
							</select>
						</div>
						<div class="text-right">
							<!-- Si $user existe, on affiche un lien de mise à jour -->
							<?php if(isset($user)): ?>
							<input class="btn btn-success" type="submit" name="update" value="Mettre à jour" />
							<!-- Sinon on afficher un lien d'enregistrement d'un nouvel utilisateur -->
							<?php else: ?>
							<input class="btn btn-success" type="submit" name="save" value="Enregistrer" />
							<?php endif; ?>
						</div>
						<!-- Si $user existe, on ajoute un champ caché contenant l'id de l'utilisateur à modifier pour la requête UPDATE -->
						<?php if(isset($user)): ?>
						<input type="hidden" name="id" value="<?= $user['id']?>" />
						<?php endif; ?>
					</form>
				</section>
			</div>
		</div>
	</body>
</html>
