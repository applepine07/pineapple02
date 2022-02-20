<?php
date_default_timezone_set("Asia/Taipei");
session_start();

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function to($url)
{
    header("location:" . $url);
}

class DB
{
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=web02";
    protected $user = "root";
    protected $pw = "";

    protected $table;
    protected $pdo;

    // construct

    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->user, $this->pw);
    }
    // find
    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= " `id` ='$id'";
        }
        // echo $sql;
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
    // all
    public function all(...$arg)
    {
        $sql = "SELECT * FROM $this->table ";
        switch (count($arg)) {
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {
                    $sql .= $arg[0];
                }
                break;
        }
        // echo $sql;
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    // math
    public function math($method, $col, ...$arg)
    {
        $sql = "SELECT $method($col) FROM $this->table ";
        switch (count($arg)) {
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
                break;
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {
                    $sql .= $arg[0];
                }
                break;
        }
        // echo $sql;
        return $this->pdo->query($sql)->fetchColumn();
    }
    // save
    public function save($array)
    {
        if (isset($array['id'])) {
            foreach ($array as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql = "UPDATE $this->table SET " . implode(",", $tmp) . " WHERE `id`={$array['id']}";
        } else {
            $sql = "INSERT INTO $this->table (`" . implode("`,`", array_keys($array)) . "`) VALUES ('" . implode("','", $array) . "')";
        }
        // echo $sql;
        return $this->pdo->exec($sql);
    }
    // del
    public function del($id)
    {
        $sql = "DELETE FROM $this->table WHERE ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= " `id` ='$id'";
        }
        // echo $sql;
        return $this->pdo->exec($sql);
    }

    // q
    public function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

// $s = new DB('students');
// $a=$s->find(1);
// $a=$s->find(['id'=>2]);
// $a=$s->all();
// $a=$s->q("SELECT * FROM `students`");
// $a=$s->math('count','major',['major'=>'美容科']);
// $s->save(['id'=>1,'parent'=>'ttt']);
// $s->del(['id'=>479]);
// echo $a;
// dd($a);
$User = new DB('user');
$Que = new DB('que');
$Log = new DB('log');
$News = new DB('news');
$View = new DB('view');

if (!isset($_SESSION['view'])) {
    if ($View->math('count', '*', ['date' => date("Y-m-d")]) > 0) {
        $view = $View->find(['date' => date("Y-m-d")]);
        $view['total']++;
        $View->save($view);
        $_SESSION['view'] = $view['total'];
    } else {
        $View->save(['date' => date("Y-m-d"), 'total' => 1]);
        $_SESSION['view'] = 1;
    }
}
