<?php
include_once "../base.php";

$news=$News->find($_POST['news']);
$type=$_POST['type'];

switch($type){
    case 1:
        $Log->del(['news'=>$news['id'],'user'=>$_SESSION['login']]);
        $news['good']--;
        $News->save($news);
        break;
        // 收回讚
        break;
    case 2:
        // 按讚
        $Log->save(['news'=>$news['id'],'user'=>$_SESSION['login']]);
        $news['good']++;
        $News->save($news);
        break;
}

?>