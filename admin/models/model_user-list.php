<?php
function getUserList($userList){
    $db = dbConnect();

//supprimer l'utilisateur dont l'ID est envoyé en paramètre URL
    if($userList){
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
    return $users = $query->fetchall();

}