<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= お気に入り登録');
debug('======================================');
debug(debugLogStart());

$u_id = $_SESSION['user_id'];
$c_id = $_POST['cinemaid'];

if(isset($u_id) && isset($c_id)){

  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM favourite WHERE user_id = :u_id AND cinema_id = :c_id';
    $data = array(':u_id' => $u_id, ':c_id' => $c_id);

    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->rowCount();

    if(!empty($result)){
      debug('お気に入り登録解除');
      $sql = 'DELETE FROM favourite WHERE user_id = :u_id AND cinema_id = :c_id';
      $data = array(':u_id' => $u_id, ':c_id' => $c_id);

      $stmt = queryPost($dbh, $sql, $data);
    }else{
      debug('お気に入り登録します');
      $sql = 'INSERT INTO favourite (cinema_id, user_id, create_date, update_date) VALUES(:c_id,:u_id,:c_date,:u_date)';
      $data = array(':c_id' => $c_id, ':u_id' => $u_id, ':c_date' => date('Y-m-d H:i:s'), ':u_date' => date('Y-m-d H:i:s'));

      $stmt = queryPost($dbh, $sql, $data);
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }
}