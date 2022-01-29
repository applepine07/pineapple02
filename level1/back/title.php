<div style="width:99%; height:87%; margin:auto; overflow:auto; border:#666 1px solid;">
    <p class="t cent botli"><?= $DB->title; ?></p>
    <form method="post" action="api/edit.php?do=<?=$DB->table;?>">
        <table width="100%">
            <tbody>
                <tr class="yel">
                    <td width="45%"><?= $DB->header; ?></td>
                    <td width="23%">替代文字</td>
                    <td width="7%">顯示</td>
                    <td width="7%">刪除</td>
                    <td></td>
                </tr>
                <?php
                $rows = $DB->all();
                // ↑為啥這裡又用DB了，不用$Title嗎?
                foreach ($rows as $row) {
                    $checked=($row['sh']==1)?'checked':'';
                ?>
                    <tr>
                        <td width="45%">
                            <img src="./img/<?= $row['img']; ?>" style="width:300px;height:30px;">
                        </td>
                        <td width="23%">
                            <input type="text" name="text[]" value="<?= $row['text']; ?>">
                        </td>
                        <td width="7%">
                            <!-- radio本就是單選，所以只會送一筆，所以不需要設成陣列 -->
                            <input type="radio" name="sh" value="<?=$row['id'];?>"<?=$checked;?>>
                        </td>
                        <td width="7%">
                            <input type="checkbox" name="del[]" value="<?= $row['id'];?>">
                        </td>
                        <td>
                        <input type="hidden" name="id[]" value="<?= $row['id'];?>">
                            <input type="button" onclick="op(&#39;#cover&#39;,&#39;#cvr&#39;,&#39;modal/upload_title.php?id=<?=$row['id'];?>&#39;)" value="更新圖片">
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <table style="margin-top:40px; width:70%;">
            <tbody>
                <tr>
                    <td width="200px">
                        <input type="button" onclick="op(&#39;#cover&#39;,&#39;#cvr&#39;,&#39;modal/title.php&#39;)" value="<?= $DB->button; ?>">
                    </td>
                    <td class="cent">
                        <input type="submit" value="修改確定"><input type="reset" value="重置">
                    </td>
                </tr>
            </tbody>
        </table>

    </form>
</div>