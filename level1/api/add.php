<?php

include "../base.php";

if (!empty($_FILES['img']['tmp_name'])) {
    move_uploaded_file($_FILES['img']['tmp_name'], "../img/" . $_FILES['img']['name']);
    $data['img'] = $_FILES['img']['name'];
} else {
    if($DB->table!='admin' && $DB->table!='menu'){
        $data['img'] = '';
    }
}

// 
switch ($DB->table) {
    case "title":
        $data['text'] = $_POST['text'];
        $data['sh'] = 0;
        break;
    case "admin":
        $data['acc'] = $_POST['acc'];
        $data['pw'] = $_POST['pw'];
        break;
    case "menu":
        $data['name'] = $_POST['name'];
        $data['href'] = $_POST['href'];
        $data['sh'] = 1;
        $data['parent'] = 0;
        break;
    default:
        // 是否存在?存在就是$_POST本身，不存在就是空''
        $data['text'] = $_POST['text'] ?? '';
        $data['sh'] = 1;
        break;
}


$DB->save($data);

// dd($_POST);
// dd($_FILES);

to("../back.php?do=" . $DB->table);
// 這邊我不懂為啥寫這樣它會變do=title?
