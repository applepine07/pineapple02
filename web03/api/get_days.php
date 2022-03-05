<?php 
include_once "../base.php";
$movie=$Movie->find($_GET['id']);
$finaldate=strtotime("+2 days",strtotime($movie['ondate']));
$gap=($finaldate-strtotime(date("Y-m-d")))/(60*60*24);


for($i=0;$i<=$gap;$i++){
    $date=date("Y-m-d",strtotime("+$i days"));
    $dateShow=date("m月d日 l",strtotime("+$i days"));
    echo "<option value='$date'>$dateShow</option>";
}