<style>
    .switch:hover {
        cursor: pointer;
        color: green;
    }
</style>
<div style="display:flex">
    <fieldset>
        <legend>目前位置：首頁>最新文章區</legend>
        <table>
            <tr>
                <td width="30%">標題</td>
                <td width="50%">內容</td>
                <td></td>
            </tr>
            <?php
            $total = $News->math("count", "*", ['sh' => 1]);
            $div = 5;
            $pages = ceil($total / $div);
            $now = $_GET['p'] ?? 1;
            $start = ($now - 1) * $div;

            $rows = $News->all(['sh' => 1], " limit $start,$div");
            // dd($rows);
            foreach ($rows as $key => $row) {
            ?>
                <tr>
                    <td class="switch"><?= $row['title']; ?></td>
                    <td class="switch">
                        <div class="short"><?= mb_substr($row['text'], 0, 20); ?>...</div>
                        <div class="full" style="display:none"><?= nl2br($row['text']); ?></div>
                    </td>
                    <td>
                        <?php
                        if (isset($_SESSION['login'])) {
                            $chk = $Log->math('count', '*', ['news' => $row['id'], 'user' => $_SESSION['login']]);
                            if ($chk > 0) {
                                echo "<a class='g' data-news='{$row['id']}' data-type='1'>收回讚</a>";
                            } else {
                                echo "<a class='g' data-news='{$row['id']}' data-type='2'>讚</a>";
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
        <div>
            <?php
            if ($now - 1 > 0) {
                $prev = $now - 1;
                echo "<a href='?do=news&p=$prev'> ";
                echo " < ";
                echo "</a>";
            }
            // 當前頁的寫法
            for ($i = 1; $i <= $pages; $i++) {
                $font = ($now == $i) ? "24px" : "16px";
                echo "<a href='?do=news&p=$i' style='font-size:$font'> ";
                echo $i;
                echo "</a>";
            }
            if (($now + 1) <= $pages) {
                $next = $now + 1;
                echo "<a href='?do=news&p=$next'> ";
                echo " > ";
                echo "</a>";
            }
            ?>
        </div>
    </fieldset>
</div>

<script>
    $(".switch").on('click', function() {
        $(this).parent().find(".short,.full").toggle();
    })

    $(".g").on("click", function() {
        let type = $(this).data('type');
        let news = $(this).data('news');
        $.post("api/good.php", {
            type,
            news
        }, () => {
            switch (type) {
                case 1:
                    $(this).text("讚");
                    $(this).data('type', 2);
                    break;
                case 2:
                    $(this).text("收回讚");
                    $(this).data('type', 1);
                    break;
            }
        })
    })
</script>