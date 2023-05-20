<?php
require_once 'header.php'; 

if(!isset($_GET['id']) || empty($_GET['id'])){
    header('Location:index.php');
    exit;
}

// Ders bilgilerini veritabanından çekiyoruz
$sorgu = $db->prepare('SELECT * FROM dersler WHERE id = ?');
$sorgu->execute([$_GET['id']]);
$ders = $sorgu->fetch(PDO::FETCH_ASSOC);

// Eğer ders yoksa veya onaylanmamışsa index sayfasına yönlendiriyoruz
if (!$ders || !$ders['onay']) {
    header('Location:index.php');
    exit;
}

$dersKategoriler = explode(',' , $ders['kategori_id']);

$kategoriler = $db->query('SELECT * FROM kategoriler ORDER BY ad ASC')->fetchAll(PDO::FETCH_ASSOC);


// Form gönderildiğinde yapılacak işlemler
if(isset($_POST['submit']))
{
    // POST verilerini alıyoruz, XSS saldırılarına karşı filtreliyoruz
    $baslik = filter_input(INPUT_POST, 'baslik', FILTER_SANITIZE_STRING);
    $icerik = filter_input(INPUT_POST, 'icerik', FILTER_SANITIZE_STRING);
    $onay = filter_input(INPUT_POST, 'onay', FILTER_VALIDATE_INT);
    $kategori_id = isset($_POST['kategori_id']) && is_array($_POST['kategori_id'])  ?   implode(',',$_POST['kategori_id']) : null;


    // Başlık ve içerik alanlarının boş olmamasını kontrol ediyoruz
    if (!$baslik || !$icerik) {
        echo 'Başlık ve İçerik alanları boş bırakılamaz.';
    } else {
        // Veritabanına güncelleme sorgusu gönderiyoruz
        $sorgu = $db->prepare('UPDATE dersler SET 
        baslik = ?,
        icerik = ?,
        onay = ? ,
        kategori_id = ?
        WHERE id = ?');
        
        $guncelle = $sorgu->execute([
            $baslik,
            $icerik,
            $onay,
            $kategori_id,
            $ders['id']
        ]);
        
        if($guncelle){
           header('Location:index.php?sayfa=oku&id=' . $ders['id']);
        } else {
            echo 'Güncelleme başarısız.';
        }
    }
}
  /*
    //get olmasının sebebi switch case yapısında linklerle oynadığımız içindir
$sorgu = $db->prepare('UPDATE dersler SET 
baslik = ?,
icerik = ?,
onay = ?
WHERE id = ?');

$guncelle = $sorgu->execute([
    'yeni baslik','yeni icerik', 1 ,2
]);

if($guncelle){
    echo 'güncelleme başarılı';
}

else{
    echo 'güncelleme başarısız';
}*/
?>

<form action="" method="post">

Başlık: 
<br>
<input type="text" value="<?php echo htmlspecialchars(isset($_POST['baslik']) ? $_POST['baslik'] : $ders['baslik']); ?>" name="baslik">
<br><br>
İçerik:
<br>
<textarea name="icerik" cols="30" rows="10"><?php echo htmlspecialchars(isset($_POST['icerik']) ? $_POST['icerik'] : $ders['icerik']); ?></textarea>
<br><br>
Kategori:
<br>
<select name="kategori_id[]" multiple size="5">
    <?php foreach($kategoriler as $kategori): ?>
            <option  <?php echo in_array($kategori['id'], $dersKategoriler)  ? 'selected' : 0 ?> value="<?php echo $kategori['id'] ?>"><?php echo $kategori['ad'] ?></option>
    <?php endforeach; ?>
</select><br><br>
Onay:
<br>
<select name="onay" >
    <option value="1" <?php if ($ders['onay'] == 1) { echo 'selected'; } ?>>Onaylandı</option>
    <option value="0" <?php if ($ders['onay'] == 0) { echo 'selected'; } ?>>Onaylanmadı</option>
</select>
<br><br>
 
<input type="hidden" name="submit" value="1">
<button type="submit">Güncelle</button>

</form>




  