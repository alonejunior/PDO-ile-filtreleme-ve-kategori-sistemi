<?php
    require_once 'header.php';
if(!isset($_GET['id']) || empty($_GET['id'])){
    header('Location:index.php');
    exit;
}

 //DELETE * FROM tanlo_adi WHERE İD

 $sorgu =$db -> prepare('DELETE  FROM dersler WHERE id=?');
$sorgu->execute([
    $_GET['id']
]);

header('Location:index.php');

?>