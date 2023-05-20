<?php
    try{     
           $db = new PDO('mysql:host=localhost;dbname=dersler' , 'root' , '');
    }
    catch(PDOException $e)
    {
       echo $e -> getMessage();
    }


?>