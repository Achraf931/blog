<?php
function cateNav(){
    $db = dbConnect();
    return $query = $db->query('SELECT * FROM category');
}