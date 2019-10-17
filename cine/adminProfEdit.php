<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= 管理者情報編集ページ');
debug('======================================');
debug(debugLogStart());

$ad_id = $_SESSION['ad_id'];
$getAdmin = getAdmin($ad_id);
debug('管理者情報：'.print_r($getAdmin,true));

if(!empty($_POST['edit'])){

  $name = $_POST['name'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  validReq($name, 'name');
  validReq($pass, 'pass');
  validReq($pass_re, 'pass_re');

  validHalf($pass, 'pass');
  validPassLength($pass, 'pass');
  unmatchPass($pass, $pass_re, 'pass_re');

  if(empty($error_msg)){

    if($name !== $getAdmin['name']){
      validReq($name, 'name');
    }
    if($pass !== password_verify($pass, $getAdmin['password']) ){
      validHalf($pass, 'pass');
      validPassLength($pass, 'pass');
    }
    if($pass !== $pass_re){
      $error_msg['pass'] = MSG04;
    }
    try{
      debug('バリデ通過');

      $dbh = dbConnect();
      $sql = 'UPDATE admin SET name = :name,password = :password WHERE id = :id';
      $data = array(':name' => $name, ':password' => password_hash($pass, PASSWORD_DEFAULT), ':id' => $getAdmin['id']);

      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        debug('上書き完了');
        $_SESSION['msg'] = adSUC02;
        header('location: adminMenu.php');
        exit();
      }
    }catch(Exception $e){
      error_log('エラー発生'.$e->getMessage());
      $error_msg['common'] = MSG06;
    }
  }
}



debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>

<?php
  $sitetitle = '管理者情報編集';
  require('head.php');
?>
  <body>
    <?php require('headerAD.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">管理者情報編集</h2>
            <p class="error_msg">
              <?php if(isset($error_msg['common'])) echo $error_msg['common']; ?>
            </p>
              <label>
                名前<span class="require_form">必須</span>
                <input class="valid_email" type="text" name="name" value="<?php echo $getAdmin['name']; ?>">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['name'])) echo $error_msg['name']; ?></p>
              <label>
                新しいパスワード<span class="require_form">必須</span>&nbsp;<span class="require_form">6文字以上</span>
                <input class="valid_area" type="password" name="pass" value="">
              </label>
              <label>
                新しいパスワード（確認）<span class="require_form">必須</span>
                <input type="password" name="pass_re" value="">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass'])) echo $error_msg['pass']; ?></p>
            <input type="submit" name="edit" class="admin_submit" value="変更する">
            <a href="adminDelProf.php" class="admin_delete">管理者を削除する</a>
          </form>
        </div>
      </section>
    </main>
    <?php require('footerAD.php'); ?>
  </body>
</html>
