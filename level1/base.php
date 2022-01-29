<?php

date_default_timezone_set("Asia/Taipei");
session_start();

class DB
{
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=web01";
    protected $user = "root";
    protected $pw = "";
    protected $pdo;
    public $table;
    public $title;
    public $button;
    public $header;
    public $append;

    // 以下供外部指定不同的table與pdo

    public function __construct($table)
    {
        //外部可使用的方法格式
        $this->table = $table;
        // ↑這行是說，在此function中外部的$table可以改變類別裡的成員table
        $this->pdo = new PDO($this->dsn, $this->user, $this->pw);
        // 外面一旦呼叫此一function，pdo就會是new PDO("mysql:host=localhost;charset=utf8;dbname=web01","root","")
        // PDO一是PHP數據對象（PHP Data Object）的簡稱，將此一物件指派給變數$pdo
        $this->setStr($table);
    }

    // 會啥要設private不懂，說什麼類別自己做的…
    private function setStr($table)
    {
        switch ($table) {
            case "title":
                // 在new一個東西的時候，我們會帶一個值(table)進來
                // 如果帶進來的table是title的話，請把我這個類別裡別的屬性，this裡面的title屬性改成網站標題管理
                $this->title = "網站標題管理";
                $this->button = "新增網站標題圖片";
                $this->header = "網站標題";
                break;
            case "ad":
                $this->title = "動態文字廣告管理";
                $this->button = "新增動態文字廣告";
                $this->header = "動態文字廣告";
                break;
            case "mvim":
                $this->title = "動畫圖片管理";
                $this->button = "新增動畫圖片";
                $this->header = "動畫圖片";
                break;
            case "image":
                $this->title = "校園映像資料管理";
                $this->button = "新增校園映像圖片";
                $this->header = "校園映像資料圖片";
                break;
            case "total":
                $this->title = "進站總人數管理";
                $this->button = "";
                $this->header = "進站總人數：";
                break;
            case "bottom":
                $this->title = "頁尾版權資料管理";
                $this->button = "";
                $this->header = "頁尾版權資料";
                break;
            case "news":
                $this->title = "最新消息資料管理";
                $this->button = "新增最新消息資料";
                $this->header = "最新消息資料內容";
                break;
            case "admin":
                $this->title = "管理者帳號管理";
                $this->button = "新增管理者帳號";
                $this->header = "帳號";
                $this->append = "密碼";
                break;
            case "menu":
                $this->title = "選單管理";
                $this->button = "新增主選單";
                $this->header = "主選單名稱";
                $this->append = "選單連結網址";
                break;
        }
    }


    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= " `id`='$id'";
        }
        //傳回這個物件的pdo去執行查詢，查詢的字串就是我們上面湊出來的$sql 
        // 然後去抓相關的欄位
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function all(...$arg)
    {
        // $arg沒有參數的情況，就執行下面這一行，table後的空白不能省
        $sql = "SELECT * FROM $this->table ";
        switch (count($arg)) {
                // 如果個數有2個，第一個參數一定是條件且是陣列
                // 第二句一定是order by/group by等字串
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " WHERE " . implode(" AND " . $arg[0] . " " . $arg[1]);
                break;
            case 1:
                if (is_array($arg[0])) {

                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " WHERE " . implode(" AND " . $arg[0]);
                } else {
                    // 如果只是字串如order by之類
                    $sql .= $arg[0];
                }
                break;
        }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function math($method, $col, ...$arg)
    {
        $sql = "SELECT $method($col) FROM $this->table ";
        switch (count($arg)) {
                // 如果個數有2個，第一個參數一定是條件且是陣列
                // 第二句一定是order by/group by等字串
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " WHERE " . implode(" AND " . $arg[0] . " " . $arg[1]);
                break;
            case 1:
                if (is_array($arg[0])) {

                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " WHERE " . implode(" AND " . $arg[0]);
                } else {
                    // 如果只是字串如order by之類
                    $sql .= $arg[0];
                }
                break;
        }
        // ↓只需它回傳一個欄位故用fetchColumn
        return $this->pdo->query($sql)->fetchColumn();
    }




    public function save($array)
    {
        // 用id的有無判斷這筆資料是要新增或更新
        if (isset($array['id'])) {
            // update
            foreach ($array as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql = "UPDATE $this->table SET " . implode(",", $tmp) . " WHERE `id`='{$array['id']}'";
            // 這個缺點在於id也位於字串中，怕會一起update雖然沒關係，需要可以想辦法移除
        } else {
            // insert
            // ('A','B') (`a`,`b`)
            $sql = "INSERT INTO $this->table (`" . implode("`,`", array_keys($array)) . "`) VALUES ('" . implode("','", $array) . "')";
        }
        // 不需它回傳，只需執行故用exec
        // dd($sql);
        return $this->pdo->exec($sql);
    }

    public function del($id)
    {
        $sql = "DELETE FROM $this->table WHERE ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= " `id`='$id'";
        }
        // 不需它回傳，只需執行故用exec
        return $this->pdo->exec($sql);
    }

    public function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}


// ----------------

function to($url)
{
    header("location:" . $url);
}

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

// ----------------

// 將物件實體化，並將total值帶入
$Ad = new DB('ad');
$Admin = new DB('admin');
$Bottom = new DB('bottom');
$Image = new DB('image');
$Menu = new DB('menu');
$Mvim = new DB('mvim');
$News = new DB('news');
$Title = new DB('title');
$Total = new DB('total');
// function本身可以是一個變數，
// 所以以下可以想像成$total=$Total->find(1)，$total等於$Total的find(1)
// 再去這個變數裡的陣列去取值，這樣echo $total['total'];
// echo $Total->find(1)['total'];
// dd($Total->all());

// 沒有存在session才要做事
// 在base檔做的改變優先度優於所有頁面的載入，因為我們在各檔的一開頭就引base檔
if (!isset($_SESSION['total'])) {
    $total = $Total->find(1);
    $total['total']++;
    // dd($total);
    $Total->save($total);
    $_SESSION['total'] = $total['total'];
}


// ----------------

// 三元
// $tt=(isset($_GET['do']))?$_GET['do']:'';
// 三元省括號加問號
// $tt=isset($_GET['do'])??'';
// isset獨有的三元連isset都可省
$tt = $_GET['do'] ?? '';
// ↑$_GET['do']有存在嗎?有就是$_GET['do']沒有就是空值

switch ($tt) {
        // 為了將每張表的$Title->title或$Ad->title改成統一的$DB->title
        // 主要用在後台，前台不會用到
    case "ad":
        $DB = $Ad;
        break;
    case "admin":
        $DB = $Admin;
        break;
    case "bottom":
        $DB = $Bottom;
        break;
    case "image":
        $DB = $Image;
        break;
    case "menu":
        $DB = $Menu;
        break;
    case "mvim":
        $DB = $Mvim;
        break;
    case "news":
        $DB = $News;
        break;
    case "total":
        $DB = $Total;
        break;

    default:
        $DB = $Title;
        break;
}
