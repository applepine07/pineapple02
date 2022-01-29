<?php

include "../base.php";

if (!empty($_FILES['img']['tmp_name'])) {
    move_uploaded_file($_FILES['img']['tmp_name'], "../img/" . $_FILES['img']['name']);
    $data['img'] = $_FILES['img']['name'];
}

$data['text'] = $_POST['text'];
$data['sh'] = 0;
$Title->save($data);

// dd($_POST);
// dd($_FILES);

to("../back.php?do=" . $Title->table);
// 這邊我不懂為啥寫這樣它會變do=title?
