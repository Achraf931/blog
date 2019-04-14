<?php

require_once 'tools/common.php';

if (isset($_POST['save'])) {
    /*    if (isset($_FILES['image']) AND !empty($_FILES['image']['name'])){
            $sizeMax = 209715;
            $extensionValid = array('jpg', 'jpeg');
            if (isset($_FILES['image']['size']) <= $sizeMax){
                $extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'),1));
                if (in_array($extensionUpload, $extensionValid)){
                    $path = 'img/article/' . $_POST['article_id'] . '.' . $extensionUpload;
                    $result = move_uploaded_file($_FILES['image']['tmp_name'], $path);
                    if ($result){
                        $insertImage = $db->prepare('INSERT INTO article (image) VALUES (?)');
                        $insertImage->execute(array($_FILES['image'] . '.' . $extensionUpload));
                    } else{
                        $msg = 'Erreur';
                    }
                } else{
                    $msg = 'L\'image doit être au format jpg, jpeg, gif ou png !';
                }
            } else{
                $msg = 'L\'image ne doit pas dépasser 200Ko !';
            }*/
    var_dump($_FILES);

    $allowedExtension = array('jpg', 'png', 'jpeg');
    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $sizeMax = 600000;
    $width = 1200;
    $height = 600;
    $dim = getimagesize($_FILES['image']['tmp_name']);

    print_r($dim);
    if (isset($_FILES['image']['size']) <= $sizeMax AND $dim[0] <= $width AND $dim[1] <= $height) {


            do {
                $newFileName = rand();
                $destination = './files/' . $newFileName . '.' . $fileExtension;
            } while (file_exists($destination));
            move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        echo 'ça fonctionne';

    } else {
        echo 'Mauvaise extension';
    }
}
?>

<form method="post" class="form-group" enctype="multipart/form-data">
    <label for="image">Image :</label>
    <input class="form-control" type="file" name="image" id="image"/>
    <img class="img-fluid py-4" src="../img/article/"
         alt="">
    <input type="hidden" name="current-image" value="">
    <button type="submit" name="save">Envoi</button>
</form>
