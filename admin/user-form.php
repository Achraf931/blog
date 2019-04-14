<?php
require_once '../tools/common.php';

if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
	header('location:../index.php');
	exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'utilisateur
if(isset($_POST['save'])){
	
    $query = $db->prepare('INSERT INTO user (firstname, lastname, password, email, is_admin, bio) VALUES (?, ?, ?, ?, ?, ?)');
    $newUser = $query->execute([
		$_POST['firstname'],
		$_POST['lastname'],
		hash('md5', $_POST['password']),
		$_POST['email'],
		$_POST['is_admin'],
		$_POST['bio'],
	]);
	
	//redirection après enregistrement
	//si $newUser alors l'enregistrement a fonctionné
	if($newUser){ 
		header('location:user-list.php');
		exit;
    }
	else{ //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
		$message = "Impossible d'enregistrer le nouvel utilisateur...";
	}
}

//Si $_POST['update'] existe, cela signifie que c'est une mise à jour d'utilisateur
if(isset($_POST['update'])){

	//début de la chaîne de caractères de la requête de mise à jour
	$queryString = 'UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, bio = :bio ';
	//début du tableau de paramètres de la requête de mise à jour
	$queryParameters = [ 
		'firstname' => $_POST['firstname'], 
		'lastname' => $_POST['lastname'], 
		'email' => $_POST['email'], 
		'bio' => $_POST['bio'], 
		'id' => $_POST['id']
	];

	//uniquement si l'admin souhaite modifier le mot de passe
	if( !empty($_POST['password'])) {
		//concaténation du champ password à mettre à jour
		$queryString .= ', password = :password ';
		//ajout du paramètre password à mettre à jour
		$queryParameters['password'] = hash('md5', $_POST['password']);
	}
	
	//fin de la chaîne de caractères de la requête de mise à jour
	$queryString .= 'WHERE id = :id';
	
	//préparation et execution de la requête avec la chaîne de caractères et le tableau de données
	$query = $db->prepare($queryString);
	$result = $query->execute($queryParameters);
	
	if($result){
        $_SESSION['message'] = 'Utilisateur mis à jour !';
        header('location:user-list.php');
		exit;
	}
	else{
        $_SESSION['message'] = 'Erreur.';
	}
}

//si on modifie un utilisateur, on doit séléctionner l'utilisateur en question (id envoyé dans URL) pour pré-remplir le formulaire plus bas
if(isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'edit'){
	$query = $db->prepare('SELECT * FROM user WHERE id = ?');
    $query->execute(array($_GET['user_id']));
	//$user contiendra les informations de l'utilisateur dont l'id a été envoyé en paramètre d'URL
	$user = $query->fetch();
}

?>

<!DOCTYPE html>
<html>
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
