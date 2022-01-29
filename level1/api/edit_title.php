<?php

include_once "../base.php";

if (isset($_POST['id'])) {
    foreach ($_POST['id'] as $key => $id) {
        if (isset($_POST['del']) && in_array($id, $_POST['del'])) {
            // 刪除
            $Title->del($id);
        } else {
            // 更新
            $data['id'] = $id;
            $data['text'] = $_POST['text'][$key];
            $data['sh'] = ($_POST['sh'] == $id) ? 1 : 0;
            $Title->save($data);
        }
    }
}
to("../back.php?do=" . $Title->table);
