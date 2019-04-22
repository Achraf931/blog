<?php

function newUser($newUser){
    $db = dbConnect();
    if($newUser){

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
            return $message = "Impossible d'enregistrer le nouvel utilisateur...";
        }
    }
}


function updateUser($updateUser){
    $db = dbConnect();
    if($updateUser){

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
}

function recupUser($recup){
    $db = dbConnect();
    if($recup){
        $query = $db->prepare('SELECT * FROM user WHERE id = ?');
        $query->execute(array($_GET['user_id']));
        //$user contiendra les informations de l'utilisateur dont l'id a été envoyé en paramètre d'URL
        return $user = $query->fetch();
    }
}