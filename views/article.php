<!DOCTYPE html>
<html lang="fr">
 <head>
	<title><?= $article['title']; ?> - Mon premier blog !</title>
	<?php require 'partials/head_assets.php'; ?>
 </head>
 <body class="article-body">
	<div class="container-fluid">
		<?php require 'partials/header.php'; ?>
		<div class="row my-3 article-content">
			<?php require './controllers/controller_nav.php'; ?>
			<main class="col-9">
				<article>
					<h1><?= $article['title']; ?></h1>
                    <img class="pb-4 img-fluid" src="img/article/<?= $article['image']; ?>" alt="">
                    <strong class="article-category">[<?= $article['name']; ?>]</strong>
					<span class="article-date">
						<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
						<?= strftime("%A %e %B %Y", strtotime($article['published_at'])); ?>
					</span>
					<div class="article-content">
						<?= $article['content']; ?>
					</div>
				</article>
			</main>
		</div>
		<?php require 'partials/footer.php'; ?>
	</div>
 </body>
</html>
