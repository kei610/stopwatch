<?php

error_reporting(E_ALL);
ini_set('display_errors','on');
ini_set('log_errors','on');
ini_set('error_log','php.log');

$debug_flg = true;

function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ:'.$str);
    }
}

function dbConnect(){

    $dsn = 'mysql:dbname=stopwatch;host=127.0.0.1;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
      // SQL実行失敗時に例外をスロー
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      // デフォルトフェッチモードを連想配列形式に設定
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
      // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      
      PDO::ATTR_EMULATE_PREPARES => false
    );
    
    $dbh = new PDO($dsn, $user, $password, $options);

    return $dbh;
}

function queryPost($dbh,$sql,$data){

    $stmt = $dbh->prepare($sql);

    if(!$stmt->execute($data)){
                            
        debug('クエリに失敗しました。');
        return 0;
        
    } else{

        debug('クエリ成功');
        return $stmt;
    }
}

// リストに表示
function getTaskTitle($id){

    $dbh = dbConnect();

    $sql = 'SELECT name FROM tasks WHERE id = :id';

    $data = array(':id' => $id);

    $stmt = queryPost($dbh,$sql,$data);

    if($stmt){
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else{
        return false;
    }
 
}
function getTask(){

    $dbh = dbConnect();

    $stmt = $dbh->query('SELECT id,name FROM tasks WHERE complete_flag = 0 LIMIT 5');

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
 
}

function validTaskDup($name){

    $dbh = dbConnect();

    $sql = 'SELECT name FROM tasks WHERE name = :name AND complete_flag = 0';

    $data = array(':name' => $name);

    $stmt = queryPost($dbh,$sql,$data);

    if($stmt){
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else{

        return false;
    }
}

// 追加
function addTask($name){

    $dbh = dbConnect();

    $sql = 'INSERT INTO tasks (name,created_date) VALUES (:name,:created_date)';

    $data = array(':name' => $name,':created_date' => date('Y-m-d H:i:s'));

    queryPost($dbh,$sql,$data);

}

// 完了
function completeTask($id,$complete_time){

    $dbh = dbConnect();

    $sql = 'UPDATE complete_flag SET tasks WHERE id = :id AND complete_flag = 0';

    $data = array(':id' => $id,':complete_flag' => 1,);

    queryPost($dbh,$sql,$data);

}

