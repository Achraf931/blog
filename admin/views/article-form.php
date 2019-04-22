<?php

require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'article
if (isset($_POST['save'])) {
    $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageInformations = getimagesize($_FILES['image']['tmp_name']);

    if (empty($_POST['title'])) {
        $message = 'Le titre est obligatoire !';
    } elseif (empty($_POST['published_at'])) {
        $message = 'La date est obligatoire !';
    } else {
        do {
            $new_file_name = rand();
            $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));

        $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        $query = $db->prepare('INSERT INTO article (title, content, summary, is_published, published_at, image) VALUES (?, ?, ?, ?, ?, ?)');
        $newArticle = $query->execute([
            $_POST['title'],
            $_POST['content'],
            $_POST['summary'],
            $_POST['is_published'],
            $_POST['published_at'],
            $new_file_name . '.' . $my_file_extension
        ]);

        $lastInsertedId = $db->lastInsertId();
        foreach ($_POST['category_id'] as $category) {
            $queryInsert = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
            $resultInsert = $queryInsert->execute(
                [
                    $lastInsertedId,
                    $category
                ]);
        }
        $insertCat = $resultInsert;

        //redirection après enregistrement
        //si $newArticle alors l'enregistrement a fonctionné
        if ($newArticle AND $insertCat) {
            //redirection après enregistrement
            $_SESSION['message'] = 'Article ajouté !';
            header('location:article-list.php');
            exit;
        } else { //si pas $newArticle => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
            $message = "Impossible d'enregistrer le nouvel article...";
        }
    }
}


//Si $_POST['update'] existe, cela signifie que c'est une mise à jour d'article
if (isset($_POST['update'])) {

    $selectImage = $db->prepare('SELECT image FROM article WHERE id = ?');
    $selectImage->execute([
        $_GET['article_id']
    ]);
    $recupImage = $selectImage->fetch();

    $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageInformations = getimagesize($_FILES['image']['tmp_name']);

    if (isset($_FILES['image']) AND empty($_FILES['image'])) {
        $message = 'L\'image est obligatoire !';
    } elseif (!in_array($my_file_extension, $allowed_extensions)) {
        $message = 'L\'extension est invalide !';
    } else {
        if (!$_FILES['image']['error'] == 4) {
            $selectImage = $db->prepare('SELECT image FROM article WHERE id = ?');
            $selectImage->execute([
                $_POST['id']
            ]);
            $recupImage = $selectImage->fetch();

            $destination = '../img/article/';
            unlink($destination . $recupImage['image']);
            do {
                $new_file_name = rand();
                $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;
            } while (file_exists($destination));

            $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);

            $query = $db->prepare('UPDATE article SET
		title = :title,
		content = :content,
		summary = :summary,
		is_published = :is_published,
		published_at = :published_at,
		image = :image
		WHERE id = :id');

            //mise à jour avec les données du formulaire
            $resultArticle = $query->execute([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'summary' => $_POST['summary'],
                'is_published' => $_POST['is_published'],
                'published_at' => $_POST['published_at'],
                'image' => $new_file_name . '.' . $my_file_extension,
                'id' => $_POST['id'],
            ]);

            $catDelete = $db->prepare('DELETE FROM article_category WHERE article_id = ?');
            $delete = $catDelete->execute(array($_POST['id']));

            foreach ($_POST['category_id'] as $category) {
                $updateCate = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
                $resultInsertCat = $updateCate->execute(
                    [
                        $_POST['id'],
                        $category
                    ]
                );
            }

            //si enregistrement ok
            if ($resultArticle) {
                $_SESSION['message'] = 'Article mis à jour !';
                header('location:article-list.php');
            } else {
                $_SESSION['message'] = 'Erreur.';
            }
        }
    }
}


//si on modifie un article, on doit séléctionner l'article en question (id envoyé dans URL) pour pré-remplir le formulaire plus bas
if (isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM article WHERE id = ?');
    $query->execute(array($_GET['article_id']));
    //$article contiendra les informations de l'article dont l'id a été envoyé en paramètre d'URL
    $article = $query->fetch();
}


/*if (isset($_POST['add_image'])) {

    $selectImages = $db->prepare('SELECT i.*, a.id as articleId
    FROM image i JOIN article a
    ON i.article_id = a.id');
    $selectImages->execute(array($_GET['article_id']));
    $recupImages = $selectImages->fetchAll();

    $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
    $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageInformations = getimagesize($_FILES['image']['tmp_name']);

    if (isset($_FILES['image']) AND !in_array($my_file_extension, $allowed_extensions)) {
        $message = 'L\'extension est invalide !';
    } else {
        do {
            $new_file_name = rand();
            $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;
        } while (file_exists($destination));

        $result = move_uploaded_file($_FILES['image']['tmp_name'], $destination);

        $query = $db->prepare('INSERT INTO image (caption, name, article_id) VALUES (?, ?, ?)');
        $resultArticles = $query->execute(array($_POST['caption'], $new_file_name . '.' . $my_file_extension, $_GET['article_id']));
        //si enregistrement ok
        if ($resultArticles) {
            $_SESSION['message'] = 'Article mis à jour !';
        } else {
            $_SESSION['message'] = 'Erreur.';
        }
    }
}

if (isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM image WHERE article_id = ?');
    $query->execute(array($_GET['article_id']));
    //$article contiendra les informations de l'article dont l'id a été envoyé en paramètre d'URL
    $articles = $query->fetch();
}*/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Administration des articles - Mon premier blog !</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">
    <?php require 'partials/header.php'; ?>
    <div class="row my-3 index-content">
        <?php require 'partials/nav.php'; ?>
        <section class="col-9">
            <header class="pb-3">
                <!-- Si $article existe, on affiche "Modifier" SINON on affiche "Ajouter" -->
                <h4><?php if (isset($article)): ?>Modifier<?php else: ?>Ajouter<?php endif; ?> un article</h4>
            </header>
            <ul class="nav nav-tabs justify-content-center nav-fill" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active <? //= isset($_POST['register']) ? 'active' : ''; ?>" data-toggle="tab"
                       href="#infos" role="tab" aria-expanded="<? //= isset($_POST['register']) ? 'true' : 'false'; ?>">Infos</a>
                </li>
                <?php if (isset($_GET['article_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <? //= isset($_POST['add_image']) ? 'active' : ''; ?>" data-toggle="tab"
                           href="#images" role="tab"
                           aria-expanded="<? //= isset($_POST['add_image']) ? 'true' : 'false'; ?>">Images</a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                <div class="bg-danger text-white">
                    <?= $message; ?>
                </div>
            <?php endif; ?>

            <div class="tab-content">
                <div class="tab-pane container-fluid active <? //= isset($_POST['register']) ? 'active' : ''; ?>"
                     id="infos"
                     role="tabpanel">
                    <!-- Si $article existe, chaque champ du formulaire sera pré-remplit avec les informations de l'article -->
                    <?php if (isset($_GET['article_id'])): ?>
                    <form action="article-form.php?article_id=<?= $article['id']; ?>&action=edit" method="post"
                          enctype="multipart/form-data">
                        <?php else: ?>
                        <form action="article-form.php" method="post" enctype="multipart/form-data">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="title">Titre :</label>
                                <input class="form-control"
                                       value="<?= isset($article) ? htmlentities($article['title']) : ''; ?>"
                                       type="text" placeholder="Titre" name="title" id="title"/>
                            </div>
                            <div class="form-group">
                                <label for="summary">Résumé :</label>
                                <input class="form-control"
                                       value="<?= isset($article) ? htmlentities($article['summary']) : ''; ?>"
                                       type="text" placeholder="Résumé" name="summary" id="summary"/>
                            </div>
                            <div class="form-group">
                                <label for="content">Contenu :</label>
                                <textarea class="form-control" name="content" id="content"
                                          placeholder="Contenu"><?= isset($article) ? htmlentities($article['content']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="image">Image :</label>
                                <input class="form-control" type="file" name="image" id="image"/>
                                <?php if (isset($article['image']) AND !empty($article['image'])): ?>
                                    <img class="img-fluid py-4"
                                         src="../img/article/<?= isset($article) ? $article['image'] : ''; ?>" alt="">
                                    <input type="hidden" name="current-image"
                                           value="<?= isset($article) ? $article['image'] : ''; ?>">
                                <?php endif; ?>

                            </div>

                            <div class="form-group">
                                <pre>
                                <?php
                                $queryCategories = $db->query('SELECT * FROM category');
                                $categories = $queryCategories->fetchAll();
                                ?>
                                <?php $selectedCat = $db->prepare('SELECT category_id FROM article_category WHERE article_id = ?');
                                $selectedCat->execute(array($_GET['article_id']));
                                $recupCat = $selectedCat->fetchAll();

                                var_dump($recupCat);
                                ?>
                                    </pre>

                                <label for="category_id">Catégorie :</label>
                                <select class="form-control" name="category_id[]" id="category_id" multiple>

                                    <?php foreach ($categories as $key => $category) : ?>

                                        <option value="<?= $category['id']; ?>" <?php foreach ($recupCat as $cats): ?>
                                            $selected=;
                                        <?php endforeach; ?> <?= isset($_GET['article_id']) && in_array($recupCat, $category) ? 'selected' : ''; ?>>
                                            <?= $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="published_at">Date de publication :</label>
                                <input class="form-control"
                                       value="<?= isset($article) ? htmlentities($article['published_at']) : ''; ?>"
                                       type="date"
                                       placeholder="Résumé" name="published_at" id="published_at"/>
                            </div>

                            <div class="form-group">
                                <label for="is_published">Publié ?</label>
                                <select class="form-control" name="is_published" id="is_published">
                                    <option value="0" <?= isset($article) && $article['is_published'] == 0 ? 'selected' : ''; ?>>
                                        Non
                                    </option>
                                    <option value="1" <?= isset($article) && $article['is_published'] == 1 ? 'selected' : ''; ?>>
                                        Oui
                                    </option>
                                </select>
                            </div>

                            <div class="text-right">
                                <!-- Si $article existe, on affiche un lien de mise à jour -->
                                <?php if (isset($article)): ?>
                                    <input class="btn btn-success" type="submit" name="update" value="Mettre à jour"/>
                                    <!-- Sinon on afficher un lien d'enregistrement d'un nouvel article -->
                                <?php else: ?>
                                    <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                                <?php endif; ?>
                            </div>

                            <!-- Si $article existe, on ajoute un champ caché contenant l'id de l'article à modifier pour la requête UPDATE -->
                            <?php if (isset($article)): ?>
                                <input type="hidden" name="id" value="<?= $article['id']; ?>"/>
                            <?php endif; ?>

                        </form>
                </div>

                <div class="tab-pane container-fluid <? //= isset($_POST['add_image']) ? 'active' : ''; ?>"
                     id="images" role="tabpanel"
                ">
                <?php if (isset($resultArticles)): ?>
                    <div class="bg-success text-white p-2 my-4">Image ajoutée avec succès !</div><?php endif; ?>

                <h5 class="mt-4">Ajouter une image :</h5>

                <form action="article-form.php?article_id=<?= $articles['id']; ?>&action=edit" method="post"
                      enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="caption">Légende :</label>
                        <input class="form-control" type="text" placeholder="Légende" name="caption"
                               id="caption"
                               value="<?= isset($articles) ? htmlentities($articles['caption']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">Fichier :</label>
                        <input class="form-control" type="file" name="image" id="image">
                    </div>

                    <input type="hidden" name="article_id" value="<?= $articles['id']; ?>">

                    <div class="text-right">
                        <input class="btn btn-success" type="submit" name="add_image" value="Enregistrer">
                    </div>
                </form>

                <div class="row">
                    <h5 class="col-12 pb-4">Liste des images :</h5>
                    <form action="article-form.php?article_id=<?= $articles['article_id']; ?>&action=edit" method="post"
                          class="col-4 my-3">
                        <?php if (isset($articles['name'])): ?>
                            <img src="../img/article/<?php echo $articles['name']; ?>" alt="" class="img-fluid">
                            <p class="my-2"></p>
                            <input type="hidden" name="img_id" value="<?= $articles['name']; ?>">
                            <div class="text-right">
                                <input class="btn btn-danger" type="submit" name="delete_image" value="Supprimer">
                            </div>
                        <?php endif; ?>
                    </form>
                </div>

            </div>
        </section>

    </div>
</div>
</body>
</html>
