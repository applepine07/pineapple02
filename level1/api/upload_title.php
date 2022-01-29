<?php

include_once "../base.php";

if (!empty($_FILES['img']['tmp_name'])) {
    // 一般要先unlink舊圖再把新圖搬到我們的指定位置
    move_uploaded_file($_FILES['img']['tmp_name'], "../img/" . $_FILES['img']['name']);
    $data['img'] = $_FILES['img']['name'];
    $data['id']=$_POST['id'];
    $Title->save($data);
}

// dd($_POST);

to("../back.php?do=" . $Title->table);
