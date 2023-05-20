<?php
    
   if(isset($_POST['ad']))
   {
    if (empty($_POST['ad'])){
        echo 'Lütfen kategori adını belirtin';
    }
    else{
 //PHP'nin "prepare" fonksiyonu, veritabanlarından veri almak veya veri eklemek gibi işlemleri gerçekleştirmeden önce SQL sorgularının hazırlanması için kullanılır.
 $sorgu = $db ->prepare('INSERT INTO kategoriler SET
 ad = ?');
//"execute", hazırlanan SQL sorgusunun çalıştırılması anlamına gelir.
$ekle =  $sorgu->execute([
     $_POST['ad']
]);

 if ($ekle){
     header('Location:index.php?sayfa=kategoriler');
 }
 else {
     echo 'eklenirken hata oluştu';
 }

    }
   

   }

?>


<form action="" method="post">

    Kategori Adı: <br>
    <input type="text" name="ad"> <br> <br>

    <button type="submit">Gönder</button>

</form>