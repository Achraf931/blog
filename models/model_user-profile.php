<?php
function updateUser($update){
    $db = dbConnect();
    if ($update) {
        //début de la chaîne de caractères de la requête de mise à jour
        $queryString = 'UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, bio = :bio ';
        //début du tableau de paramètres de la requête de mise à jour
        $queryParameters = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'bio' => $_POST['bio'],
            'id' => $_SESSION['user']['id']
        ];

        //uniquement si l'admin souhaite modifier le mot de passe
        if (!empty($_POST['password']) AND !empty($_POST['password_confirm']) AND $_POST['password'] == $_POST['password_confirm']) {
            //concaténation du champ password à mettre à jour
            $queryString .= ', password = :password ';
            //ajout du paramètre password à mettre à jour
            $queryParameters['password'] = hash('md5', $_POST['password']);
        } else {
            return $message = 'Vos mots de passe ne correspondent pas !';
        }

        //fin de la chaîne de caractères de la requête de mise à jour
        $queryString .= 'WHERE id = :id';

        //préparation et execution de la requête avec la chaîne de caractères et le tableau de données
        $query = $db->prepare($queryString);
        $result = $query->execute($queryParameters);

        if ($result) {
            return $message = '<div class="bg-success text-white p-2 mb-4">Modification réussi !</div>';
            $_SESSION['user']['firstname'] = $_POST['firstname'];
        } else {
            return $message = 'Modification impossible !';
        }
    }
}

function recupInfo($recup){
    $db = dbConnect();

    $reqUser = $db->prepare("SELECT * FROM user WHERE id = ?");
    $reqUser->execute(array($_SESSION['user']['id']));
    return $user = $reqUser->fetch();
}