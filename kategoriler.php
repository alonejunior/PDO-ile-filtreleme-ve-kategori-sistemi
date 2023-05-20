   <?php
       require_once 'header.php';
   ?>
   
   <a href="index.php?sayfa=kategori_ekle">[Kategori Ekle]</a>
<?php
    
//(dersler.kategori_id , kategoriler.id) bu sorguddaki find in set kullanımı dersler.kategori_id nin kategoriler.id içinde var olup olmadığını kontrol 
$kategoriler = $db->query('SELECT kategoriler.*,COUNT(dersler.id) as toplamDers FROM kategoriler
LEFT JOIN dersler ON FIND_IN_SET( kategoriler.id ,dersler.kategori_id )
GROUP BY kategoriler.id')->fetchAll(PDO::FETCH_ASSOC);

?>

<ul>
    <?php foreach ($kategoriler as $kategori): ?>

            <li>
               <a href="index.php?sayfa=kategori&id=<?php echo $kategori['id'] ?>">
               <?php echo $kategori['ad'] ?>
               (<?php echo $kategori['toplamDers'];  ?>)
               </a>
            </li>

    <?php endforeach; ?>
</ul>
