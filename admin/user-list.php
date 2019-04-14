<?php

require_once '../tools/common.php';

if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
	header('location:../index.php');
	exit;
}

//supprimer l'utilisateur dont l'ID est envoyé en paramètre URL
if(isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){

	$query = $db->prepare('DELETE FROM user WHERE id = ?');
	$result = $query->execute([
		$_GET['user_id']
	]);
	
	//générer un message à afficher plus bas pour l'administrateur
	if($result){
        $_SESSION['message'] = 'Suppression efféctuée !';
	}
	else{
        $_SESSION['message'] = "Impossible de supprimer la séléction !";
	}
}

//séléctionner tous les utilisateurs pour affichage de la liste
$query = $db->query('SELECT * FROM user ORDER BY id DESC');
$users = $query->fetchall();
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
					<header class="pb-4 d-flex justify-content-between">
						<h4>Liste des utilisateurs</h4>
						<a class="btn btn-primary" href="user-form.php">Ajouter un utilisateur</a>
					</header>
                    <?php if(isset($_SESSION['message'])): //si un message a été généré plus haut, l'afficher ?>
                        <div class="bg-success text-white p-2 mb-4">
                            <?= $_SESSION['message']; ?>
                            <?php unset($_SESSION['message']); ?>
                        </div>
                    <?php endif; ?>
					<?php if($users): ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Admin</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($users as $user): ?>
							<tr>
								<!-- htmlentities sert à écrire les balises html sans les interpréter -->
								<th><?= htmlentities($user['id']); ?></th>
								<td><?= htmlentities($user['firstname']); ?></td>
								<td><?= htmlentities($user['lastname']); ?></td>
								<td><?= htmlentities($user['email']); ?></td>
								<td><?= $user['is_admin'] == 1 ? 'Oui' : 'Non' ?></td>
								<td>
									<a href="user-form.php?user_id=<?= $user['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
									<a onclick="return confirm('Are you sure?')" href="user-list.php?user_id=<?= $user['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php else: ?>
						Aucun utilisateur enregistré.
					<?php endif; ?>
				</section>
			</div>
		</div>
	</body>
</html>
