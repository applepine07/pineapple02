<fieldset>
    <legend>最新文章管理</legend>
<form action="api/news_admin.php" method="post">
    <table>
        <tr>
            <td width="10%">編號</td>
            <td width="75%">標題</td>
            <td width="10%">顯示</td>
            <td width="10%">刪除</td>
        </tr>
        <?php
    
        $total=$News->math("count","*");
        $div=3;
        $pages=ceil($total/$div);
        $now=$_GET['p']??1;
        $start=($now-1)*$div;
    
        $rows=$News->all(" limit $start,$div");
        foreach ($rows as $key => $row) {
            $chk=($row['sh']==1)?"checked":"";
        ?>
        <tr>
            <td><?=$start+1+$key;?></td>
            <td><?=$row['title'];?></td>
            <td>
                <input type="checkbox" name="sh[]" value="<?=$row['id'];?>" <?=$chk;?> >
            </td>
            <td>
                <input type="checkbox" name="del[]" value="<?=$row['id'];?>">
                <input type="hidden" name="id[]" value="<?=$row['id'];?>">

            </td>
        </tr>
        <?php
        }
        ?>
    </table>
    <div class='ct'>
    <?php
    
    if(($now-1)>0){
        $prev=$now-1;
        echo "<a href='?do=news&p=$prev'> ";
        echo " < ";
        echo " </a>";
    }
    
    
    for($i=1;$i<=$pages;$i++){
        $font=($now==$i)?'24px':'16px';
        echo "<a href='?do=news&p=$i' style='font-size:$font'> ";
        echo $i;
        echo " </a>";
    }
    
    if(($now+1)<=$pages){
        $next=$now+1;
        echo "<a href='?do=news&p=$next'> ";
        echo " > ";
        echo " </a>";
    }
    
    ?>
    </div>
    <div class="ct"><input type="submit" value="確定修改"></div>
</form>
</fieldset>