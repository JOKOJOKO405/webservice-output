<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= 映画情報削除ページ');
debug('======================================');
debug(debugLogStart());

$c_id = $_GET['c_id'];
debug('映画情報削除要求がありました。シネマID：'.print_r($c_id,true));

try{
    $dbh = dbConnect();
    $sql = 'UPDATE cinema SET delete_flg = 1 WHERE id = :c_id AND delete_flg = 0';
    $data = array(':c_id' => $c_id);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      debug('削除成功');
      $_SESSION['msg'] = adSUC03;
      header('location: adminMenu.php');
      exit();
    }else{
      debug('失敗');
    }

  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
    global $error_msg;
    $error_msg['common'] = MSG06;
  }