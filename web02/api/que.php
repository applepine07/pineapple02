<?php
include_once "../base.php";
if($_POST['subject']!=""){
    $subject=$_POST['subject'];
    $Que->save(['text'=>$subject,'parent'=>0,'count'=>0]);
}

$parent_id=$Que->math("max","id");
foreach($_POST['options'] as $opt){
    $Que->save(['text'=>$opt,'parent'=>$parent_id,'count'=>0]);
}



to("../back.php?do=que");
?>