<?php
include_once "../base.php";

foreach($_POST['id'] as $id){
    if(isset($_POST['del']) && in_array($id,$_POST['del'])){
        $News->del($id);
    }else{
        $news=$News->find($id);
        $news['sh']=(isset($_POST['sh']) && in_array($id,$_POST['sh']))?1:0;
        $News->save($news);
    }
}

// foreach($_POST['del'] as $id){
//     $News->del($id);
// }

// $news=$News->all();

// foreach($news as $n){
//     $news=$News->del($id);
//     $n['sh']=(isset($_POST['sh']) && in_array($n['id'],$_POST['sh']))?1:0;
//     $News->save($n);
// }

to("../back.php?do=news");

?>