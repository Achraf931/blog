<?php
function verifAdmin($verif){
    if($verif){
        header('location:../index.php');
        exit;
    }
}