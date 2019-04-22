<!DOCTYPE html>
<html lang="fr">
<head>

    <title>Profile - Mon premier blog !</title>

    <meta charset="utf-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.1/jquery.fancybox.min.css"/>
    <link rel="stylesheet" href="css/main.css">

</head>
<body class="article-body">
<div class="container-fluid">

    <?php require './partials/header.php'; ?>

    <div class="row my-3 article-content">


        <?php require './controllers/controller_nav.php'; ?>


        <main class="col-9">

            <form action="index.php?page=user-profile" method="post" class="p-4 row flex-column">

                <p class="text-danger"><?= isset($message) ? $message : ''; ?></p>

                <h4 class="pb-4 col-sm-8 offset-sm-2">Mise à jour des informations utilisateur</h4>

                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="firstname">Prénom</label>
                    <input class="form-control"
                           value="<?= isset($message) ? $_POST['firstname'] : $user['firstname']; ?>" type="text"
                           placeholder="Prénom" name="firstname" id="firstname"/>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="lastname">Nom de famille</label>
                    <input class="form-control" value="<?= isset($message) ? $_POST['lastname'] : $user['lastname']; ?>"
                           type="text" placeholder="Nom de famille" name="lastname" id="lastname"/>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="email">Email</label>
                    <input class="form-control" value="<?= isset($message) ? $_POST['email'] : $user['email']; ?>"
                           type="email" placeholder="Email" name="email" id="email"/>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="password">Mot de passe (uniquement si vous souhaitez modifier votre mot de passe
                        actuel)</label>
                    <input class="form-control" value="" type="password" placeholder="Mot de passe" name="password"
                           id="password"/>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="password_confirm">Confirmation du mot de passe (uniquement si vous souhaitez modifier
                        votre mot de passe actuel)</label>
                    <input class="form-control" value="" type="password" placeholder="Confirmation du mot de passe"
                           name="password_confirm" id="password_confirm"/>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="bio">Biographie</label>
                    <textarea class="form-control" name="bio" id="bio"
                              placeholder="Ta vie Ton oeuvre..."><?= isset($message) ? $_POST['bio'] : $user['bio']; ?></textarea>
                </div>

                <div class="text-right col-sm-8 offset-sm-2">
                    <input class="btn btn-success" type="submit" name="update" value="Valider"/>
                </div>

            </form>
        </main>
    </div>
    <?php require 'partials/footer.php'; ?>


    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.1/jquery.fancybox.min.js"></script>

    <script src="../js/main.js"></script>

</div>
</body>
</html>

