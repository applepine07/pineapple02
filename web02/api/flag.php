<?php include_once "../base.php";
$id=$_GET['id'];
$flag=$Que->find($id);
$flag['sh']=($flag['sh']+1)%2;
$Que->save($flag);
to("../back.php?do=poll");

?>