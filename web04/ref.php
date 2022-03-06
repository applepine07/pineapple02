<?php
if(isset($_POST['bottom'])){
    $Bot->save(['id'=>1,
                'bottom'=>$_POST['bottom']]);
}
?>