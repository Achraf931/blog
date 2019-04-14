<?php

require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'article
if (isset($_POST['save'])) {

    $query = $db->prepare('INSERT INTO article (title, content, summary, is_published, published_at, image) VALUES (?, ?, ?, ?, ?, ?)');
    $newArticle = $query->execute([
        $_POST['title'],
        $_POST['content'],
        $_POST['summary'],
        $_POST['is_published'],
        $_POST['published_at'],
        basename($_FILES['image']['name'])
    ]);
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];
    move_uploaded_file($file_tmp, "../img/article/" . $file_name);

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


//Si $_POST['update'] existe, cela signifie que c'est une mise à jour d'article
if (isset($_POST['update'])) {

    $query = $db->prepare('UPDATE article SET
		title = :title,
		content = :content,
		summary = :summary,
		is_published = :is_published,
		published_at = :published_at,
		image = :image
		WHERE id = :id'
    );

    //mise à jour avec les données du formulaire
    $resultArticle = $query->execute([
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'summary' => $_POST['summary'],
        'is_published' => $_POST['is_published'],
        'published_at' => $_POST['published_at'],
        'image' => basename($_FILES['image']['name']),
        'id' => $_POST['id'],
    ]);
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = $_FILES['image']['name'];
    move_uploaded_file($file_tmp, "../img/article/" . $file_name);


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

    } else {
        $message = 'Erreur.';
    }
}


//si on modifie un article, on doit séléctionner l'article en question (id envoyé dans URL) pour pré-remplir le formulaire plus bas
if (isset($_GET['article_id']) && isset($_GET['action']) && $_GET['action'] == 'edit') {

    $query = $db->prepare('SELECT * FROM article WHERE id = ?');
    $query->execute(array($_GET['article_id']));
    //$article contiendra les informations de l'article dont l'id a été envoyé en paramètre d'URL
    $article = $query->fetch();
}
?>

<!DOCTYPE html>
<html>
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
            <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                <div class="bg-danger text-white">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
            <!-- Si $article existe, chaque champ du formulaire sera pré-remplit avec les informations de l'article -->
            <form action="article-form.php" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="title">Titre :</label>
                    <input class="form-control" value="<?= isset($article) ? htmlentities($article['title']) : ''; ?>"
                           type="text" placeholder="Titre" name="title" id="title"/>
                </div>
                <div class="form-group">
                    <label for="summary">Résumé :</label>
                    <input class="form-control" value="<?= isset($article) ? htmlentities($article['summary']) : ''; ?>"
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
                    <img class="img-fluid py-4" src="../img/article/<?= isset($article) ? $article['image'] : ''; ?>"
                         alt="">
                    <input type="hidden" name="current-image" value="<?= isset($article) ? $article['image'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="category_id">Catégorie :</label>
                    <select class="form-control" name="category_id[]" id="category_id" multiple>
                        <?php
                        $queryCategories = $db->query('SELECT * FROM category');
                        $categories = $queryCategories->fetchAll();
                        ?>
                        <?php foreach ($categories as $key => $category) : ?>
                            <option value="<?= $category['id']; ?>" <?= isset($article) && $article['id'] == $category['id'] ? 'selected' : ''; ?>>
                                <?= $category['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="published_at">Date de publication :</label>
                    <input class="form-control"
                           value="<?= isset($article) ? htmlentities($article['published_at']) : ''; ?>" type="date"
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
        </section>
    </div>
</div>
</body>
</html>
