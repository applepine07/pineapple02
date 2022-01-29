<div style="width:99%; height:87%; margin:auto; overflow:auto; border:#666 1px solid;">
    <p class="t cent botli"><?= $DB->title; ?></p>
    <form method="post" action="api/edit_ad.php">
        <table width="100%">
            <tbody>
                <tr class="yel">
                    <td width="80%">替代文字</td>
                    <td width="10%">顯示</td>
                    <td width="10%">刪除</td>
                </tr>
                <?php
                $rows = $DB->all();
                // ↑為啥這裡又用DB了，不用$Title嗎?
                foreach ($rows as $row) {
                    $checked = ($row['sh'] == 1) ? 'checked' : '';
                ?>
                    <tr>
                        <td>
                            <input type="text" name="text[]" value="<?= $row['text']; ?>">
                        </td>
                        <td>
                            <!-- radio本就是單選，所以只會送一筆，所以不需要設成陣列 -->
                            <input type="checkbox" name="sh[]" value="<?= $row['id']; ?>" <?= $checked; ?>>
                        </td>
                        <td>
                            <input type="checkbox" name="del[]" value="<?= $row['id']; ?>">
                            <input type="hidden" name="id[]" value="<?= $row['id']; ?>">
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
                        <input type="button" onclick="op(&#39;#cover&#39;,&#39;#cvr&#39;,&#39;modal/ad.php&#39;)" value="<?= $DB->button; ?>">
                    </td>
                    <td class="cent">
                        <input type="submit" value="修改確定"><input type="reset" value="重置">
                    </td>
                </tr>
            </tbody>
        </table>

    </form>
</div>