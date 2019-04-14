<?php
require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'une catégorie
if (isset($_POST['save'])) {
    $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageInformations = getimagesize($_FILES['image']['tmp_name']);

    if (empty($_POST['name'])) {
        $message = 'Le nom est obligatoire !';
    } elseif (isset($_FILES['image']) AND empty($_FILES['image'])) {
        $message = 'L\'image est obligatoire !';
    } elseif (!in_array($my_file_extension, $allowed_extensions)) {
        $message = 'L\'extension est invalide !';
    } else {
        do {
            $new_file_name = rand();
            $destination = '../img/category/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));

        $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);

        $query = $db->prepare('INSERT INTO category (name, description, image) VALUES (?, ?, ?)');
        $newCategory = $query->execute([
            $_POST['name'],
            $_POST['description'],
            $new_file_name . '.' . $my_file_extension
        ]);

        if ($newCategory) {
            header('location:category-list.php');
            exit;
        } else {
            $message = "Impossible d'enregistrer la nouvelle categorie...";
        }
    }
}

//Si $_POST['update'] existe, cela signifie que c'est une mise à jour de catégorie
if (isset($_POST['update'])) {
    $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageInformations = getimagesize($_FILES['image']['tmp_name']);

    if (isset($_FILES['image']) AND empty($_FILES['image'])) {
        $message = 'L\'image est obligatoire !';
    } elseif (!in_array($my_file_extension, $allowed_extensions)) {
        $message = 'L\'extension est invalide !';
    } else {
        if (!$_FILES['image']['error'] == 4) {
            $selectImage = $db->prepare('SELECT image FROM category WHERE id = ?');
            $selectImage->execute([
                $_POST['id']
            ]);
            $recupImage = $selectImage->fetch();

            $destination = '../img/category/';
            unlink($destination . $recupImage['image']);
            do {
                $new_file_name = rand();
                $destination = '../img/category/' . $new_file_name . '.' . $my_file_extension;
            } while (file_exists($destination));

            $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);

            $query = $db->prepare('UPDATE category SET
		name = :name,
		description = :description,
		image = :image
		WHERE id = :id'
            );
            //données du formulaire
            $result = $query->execute([
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'image' => $new_file_name . '.' . $my_file_extension,
                'id' => $_POST['id']
            ]);

            if ($result) {
                $_SESSION['message'] = 'Categorie mise à jour !';
                header('location:category-list.php');
                exit;
            } else {
                $_SESSION['message'] = "Impossible d'enregistrer la nouvelle categorie...";
            }
        }
    }
}

//si on modifie une catégorie, on doit séléctionner la catégorie en question (id envoyé dans URL) pour pré-remplir le formulaire plus bas
if (isset($_GET['category_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
    $query = $db->prepare('SELECT * FROM category WHERE id = ?');
    $query->execute(array($_GET['category_id']));
    //$category contiendra les informations de la catégorie dont l'id a été envoyé en paramètre d'URL
    $category = $query->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administration des catégories - Mon premier blog !</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">
    <?php require 'partials/header.php'; ?>
    <div class="row my-3 index-content">
        <?php require 'partials/nav.php'; ?>
        <section class="col-9">
            <header class="pb-3">
                <!-- Si $category existe, on affiche "Modifier" SINON on affiche "Ajouter" -->
                <h4><?php if (isset($category)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> une catégorie</h4>
            </header>
            <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                <div class="bg-danger text-white">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
            <!-- Si $category existe, chaque champ du formulaire sera pré-remplit avec les informations de la catégorie -->
            <form action="category-form.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom :</label>
                    <input class="form-control" value="<?= isset($category) ? htmlentities($category['name']) : ''; ?>"
                           type="text" placeholder="Nom" name="name" id="name"/>
                </div>
                <div class="form-group">
                    <label for="description">Description : </label>
                    <input class="form-control"
                           value="<?= isset($category) ? htmlentities($category['description']) : ''; ?>" type="text"
                           placeholder="Description" name="description" id="description"/>
                </div>
                <div class="form-group">
                    <label for="image">Image :</label>
                    <input class="form-control" type="file" name="image" id="image"/>
                    <img class="img-fluid py-4" src="../img/category/<?= isset($category) ? $category['image'] : ''; ?>"
                         alt="">
                    <input type="hidden" name="current-image"
                           value="<?= isset($category) ? $category['image'] : ''; ?>">
                </div>
                <div class="text-right">
                    <!-- Si $category existe, on affiche un lien de mise à jour -->
                    <?php if (isset($category)): ?>
                        <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>
                        <!-- Sinon on afficher un lien d'enregistrement d'une nouvelle catégorie -->
                    <?php else: ?>
                        <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                    <?php endif; ?>
                </div>
                <!-- Si $category existe, on ajoute un champ caché contenant l'id de la catégorie à modifier pour la requête UPDATE -->
                <?php if (isset($category)): ?>
                    <input type="hidden" name="id" value="<?= $category['id'] ?>"/>
                <?php endif; ?>
            </form>
        </section>
    </div>
</div>
</body>
</html>
