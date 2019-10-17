<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= ユーザー情報削除実行ページ');
debug('======================================');
debug(debugLogStart());

$u_id = $_SESSION['user_id'];
$getUser = getUser($u_id);
debug('ユーザー情報：'.print_r($getUser,true));

debug('ユーザー情報を削除します');
  $email = $getUser['email'];

  try {
    $dbh = dbConnect();
    $sql = 'DELETE FROM users WHERE id = :id AND email = :email';
    $data = array(':id' => $u_id, ':email' => $email);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      debug('ユーザー情報が削除されました');
      debug('ユーザー情報：'.print_r($getUser['name'],true));
      session_destroy();
      header('location: index.php');
      exit();
    }else{
      debug('クエリ失敗');
    }
  } catch (Exception $e) {
  error_log('エラー発生'.$e->getMessage());
  $error_msg['common'] = MSG06;
}