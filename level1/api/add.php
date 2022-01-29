<?php

include "../base.php";

if (!empty($_FILES['img']['tmp_name'])) {
    move_uploaded_file($_FILES['img']['tmp_name'], "../img/" . $_FILES['img']['name']);
    $data['img'] = $_FILES['img']['name'];
}else{
    // 因為我們資料庫設不能空值，所以至少要傳個空白不然不能存到資料庫
    $data['img']='';
}

$data['text'] = $_POST['text'];
$data['sh'] = 0;
$DB->save($data);

// dd($_POST);
// dd($_FILES);

to("../back.php?do=" . $DB->table);
// 這邊我不懂為啥寫這樣它會變do=title?
