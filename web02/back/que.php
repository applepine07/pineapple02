<fieldset>
    <legend>新增問卷</legend>
    <form action="api/que.php" method="post">
        <div style="display: flex;">
            <div class="clo">問卷名稱</div>
            <div>
                <input type="text" name="subject">
            </div>
        </div>
        <div class="clo" id="opt">
            <div>
                <input type="text" name="options[]">
                <input type="button" value="更多" onclick="more()">
            </div>
        </div>
        <div>
            <div>
                <input type="submit" value="新增"> |
                <input type="reset" value="清空">
            </div>
        </div>
    </form>
</fieldset>

<script>
    function more() {
        let opt = `
        <div>
            <input type="text" name="options[]">
        </div>
        `;
        $("#opt").prepend(opt);
    }
</script>