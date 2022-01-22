<?php

date_default_timezone_set("Asia/Taipei");
session_start();

class DB
{
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=web01";
    protected $user = "root";
    protected $pw = "";
    protected $table;
    protected $pdo;

    // 以下供外部指定不同的table與pdo

    public function __construct($table)
    {
        //外部可使用的方法格式
        $this->table = $table;
        // ↑這行是說，在此function中外部的$table可以改變類別裡的成員table
        $this->pdo = new PDO($this->dsn, $this->user, $this->pw);
        // 外面一旦呼叫此一function，pdo就會是new PDO("mysql:host=localhost;charset=utf8;dbname=web01","root","")
        // PDO一是PHP數據對象（PHP Data Object）的簡稱，將此一物件指派給變數$pdo
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

function dd($array){
    print_r($array);
}

// ----------------

// 將物件實體化，並將total值帶入
$Ad=new DB('ad');
$Admin=new DB('admin');
$Bottom=new DB('bottom');
$Image=new DB('image');
$Menu=new DB('menu');
$Mvim=new DB('mvim');
$News=new DB('news');
$Title=new DB('title');
$Total=new DB('total');
// function本身可以是一個變數，
// 所以以下可以想像成$total=$Total->find(1)，$total等於$Total的find(1)
// 再去這個變數裡的陣列去取值，這樣echo $total['total'];
// echo $Total->find(1)['total'];
// dd($Total->all());

// 沒有存在session才要做事
// 在base檔做的改變優先度優於所有頁面的載入，因為我們在各檔的一開頭就引base檔
if(!isset($_SESSION['total'])){
    $total=$Total->find(1);
    $total['total']++;
    dd($total);
    $Total->save($total);
    $_SESSION['total']=$total['total'];
}

