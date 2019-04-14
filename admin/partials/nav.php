<?php
	//nombre d'enregistrements de la table user
	$nbUsers = $db->query("SELECT COUNT(*) FROM user")->fetchColumn();
	//nombre d'enregistrements de la table category
	$nbCategories = $db->query("SELECT COUNT(*) FROM category")->fetchColumn();
	//nombre d'enregistrements de la table article
	$nbArticles = $db->query("SELECT COUNT(*) FROM article")->fetchColumn();
?>
<nav class="col-3 py-2 categories-nav">
	<a class="d-block btn btn-warning mb-4 mt-2" href="../index.php">Site</a>
	<ul>
		<li><a href="user-list.php">Gestion des utilisateurs (<?php echo $nbUsers; ?>)</a></li>
		<li><a href="category-list.php">Gestion des catégories (<?php echo $nbCategories; ?>)</a></li>
		<li><a href="article-list.php">Gestion des articles (<?php echo $nbArticles; ?>)</a></li>
	</ul>
</nav>
