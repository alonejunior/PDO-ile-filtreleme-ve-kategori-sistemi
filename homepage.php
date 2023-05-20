<h3>Ders Listesi</h3>
<form action="" method="get">
    <input type="text" class="tarih" name="baslangic" placeholder="Başlangıç Tarihi">
    <input type="text" class="tarih" name="bitis" placeholder="Bitiş Tarihi"> <br> <br>
    <input type="text" value="<?php echo isset($_GET['arama']) ? $_GET['arama'] : '' ?>" name="arama" placeholder="Derslerde ara...">
    <button type="submit">Arama</button>
</form>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $('.tarih').datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>
<?php

$where = array(); // Boş bir dizi olarak tanımlanmalı
//Veritabanı Sonuçlarının Birleştirilmesi: Özellikle gruplama işlemlerinin yapıldığı sorgularda, "GROUP_CONCAT" işlevi kullanılarak bir grup içindeki alan değerlerinin birleştirilmesi 
$sql = 'SELECT dersler.id, dersler.baslik, GROUP_CONCAT(kategoriler.ad) as kategori_adi,GROUP_CONCAT(kategoriler.id) as kategori_id , dersler.onay FROM dersler 
INNER JOIN kategoriler ON FIND_IN_SET (kategoriler.id , dersler.kategori_id)';

if (isset($_GET['arama']) && !empty($_GET['arama'])) {
    $where[] = '(dersler.baslik LIKE "%' . $_GET['arama'] . '%" || dersler.icerik LIKE "%' . $_GET['arama'] . '%")';
}

if (isset($_GET['baslangic']) && !empty($_GET['baslangic']) && isset($_GET['bitis']) && !empty($_GET['bitis'])) {
    $where[] = 'dersler.tarih BETWEEN "' . $_GET['baslangic'] . '00:00:00" AND "' . $_GET['bitis'] . ' 23:59:59"';
}

if (count($where) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= 'GROUP BY dersler.id
 ORDER BY dersler.id DESC';

echo $sql;

$dersler = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>
<?php if ($dersler): ?>
    <ul>
        <?php foreach ($dersler as $ders): ?>
            <li>
                <?php echo $ders['baslik'] ?>
                <?php
                 $kategoriAdlari = explode(',', $ders['kategori_adi']);
                 $kategoriIdleri = explode(',', $ders['kategori_id']);
                  foreach ($kategoriAdlari as $key => $val) {
                    echo '<a href="index.php?sayfa=kategori&id=' . $kategoriIdleri[$key] .  '">'. $val . '</a>';
                  }
                  ?>
                (<?php echo $ders['kategori_id'] ?>)
                <div>
                    <?php if ($ders['onay'] == 1): ?>
                        <a href="index.php?sayfa=oku&id=<?php echo $ders['id'] ?>">[OKU]</a>
                    <?php endif; ?>
                    <a href="index.php?sayfa=guncelle&id=<?php echo $ders['id'] ?>">[DÜZENLE]</a>
                    <a href="index.php?sayfa=sil&id=<?php echo $ders['id'] ?>">[SİL]</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div>
        <?php if (isset($_GET['arama'])): ?>
            Aradığınız kritere uygun ders bulunamadı!
        <?php else: ?>
            Henüz eklenmiş ders bulunmuyor

    <?php endif;  ?>
</div>

<?php endif; ?>