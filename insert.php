
<?php
require_once 'baglan.php';
//mysql sorgum : INSERT INTO tablo_adi SET kol1 = değer1

// $db->query('INSERT INTO dersler SET baslik = "başlık" , icerik = "deneme içcerikk", onay =1');
//bu şekilde de kullanılabilir fakat burada dışarıdan mudahale edilme şansı vardır


/*
$sorgu = $db->prepare('INSERT INTO dersler SET
baslik = ?,
icerik = ?,
onay = ?');

// ? işareti kullanmazsak excuel injeciton dediğimiz açıklar oluşur

$ekle = $sorgu->execute([
    'new query','contents',1
]);

if($ekle)
{
    echo 'verileriniz başarıyla eklendi';
}

else{
    $hata = $sorgu->errorInfo();
    echo 'MySQL Hatası:' . $hata[2];
}
*/

$kategoriler = $db->query('SELECT * FROM kategoriler ORDER BY ad ASC')->fetchAll(PDO::FETCH_ASSOC);
print_r($kategoriler);
//form gönderilmiş
if(isset($_POST['submit'])){
    $baslik =  isset($_POST['baslik']) ?  $_POST['baslik'] : null;
    $icerik =  isset($_POST['icerik']) ?  $_POST['icerik'] : null;
    $onay = isset($_POST['onay']) ?  $_POST['onay'] : 0;
    $kategori_id = isset($_POST['kategori_id']) &&  is_array($_POST['kategori_id']) ?  implode(',' , $_POST['kategori_id']) : null;


    if (!$baslik){
    echo 'başlık ekleyin';
    }

    elseif(!$icerik)
    {
        echo 'icerik ekleyin';
    }
    elseif(!$kategori_id){
        echo 'kategori seçin';
    }

    else{
       //ekleme işlemi
       $sorgu = $db ->prepare('INSERT INTO dersler SET 
       baslik = ?,
       icerik = ?,
       onay = ?,
       kategori_id = ?');

       $ekle = $sorgu->execute([
        $baslik,$icerik,$onay,$kategori_id
       ]);

       $sonId = $db->lastInsertId();

       if($ekle){
        header('Location:index.php?sayfa=oku&id=' . $sonId);
       }
       else
       {
        $hata = $sorgu->errorInfo();
        echo 'MySQL Hatası:' . $hata[2];
       }
    }

}

?>


<form action="" method="post">

Başlık: 
<br>
<input type="text" value="<?php echo isset($_POST['baslik']) ? $_POST['baslik'] :  ''  ?>" name="baslik">
<br><br>
İçerik:
<br>
<textarea name="icerik" value=""  cols="30" rows="10"><?php echo isset($_POST['icerik']) ? $_POST['icerik'] : ''   ?></textarea>
<br><br>
Kategori:
<br>
<select name="kategori_id[]" multiple size="5">
    <?php foreach($kategoriler as $kategori): ?>
            <option value="<?php echo $kategori['id'] ?>"><?php echo $kategori['ad'] ?></option>
    <?php endforeach; ?>
</select><br><br>
Onay:
<br>
<select name="onay">
        <option value="1">Onaylandı</option>
        <option value="0">Onaylanmadı</option>
</select>
 <br> <br>
 
 <input type="hidden" name="submit" value="1">
 <button type="submit">Gönder</button>

</form>