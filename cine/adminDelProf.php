<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= 管理者情報削除ページ');
debug('======================================');
debug(debugLogStart());

$ad_id = $_SESSION['ad_id'];
$getAdmin = getAdmin($ad_id);
debug('管理者情報：'.print_r($getAdmin,true));

debug('管理者情報を削除します');
  $name = $getAdmin['name'];

  try {
    $dbh = dbConnect();
    $sql = 'DELETE FROM admin WHERE id = :id AND name = :name';
    $data = array(':id' => $ad_id, ':name' => $name);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      debug('管理者情報が削除されました');
      debug('管理者情報：'.print_r($getAdmin['name'],true));
      session_destroy();
      header('location: login_admin.php');
      exit();
    }else{
      debug('クエリ失敗');
    }
  } catch (Exception $e) {
  error_log('エラー発生'.$e->getMessage());
  $error_msg['common'] = MSG06;
}