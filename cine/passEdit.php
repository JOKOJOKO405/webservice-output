<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= パスワード変更ページ');
debug('======================================');
debug(debugLogStart());

$u_id = $_SESSION['user_id'];
$dbHoldData = getUser($u_id);


if(!empty($_POST)){
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  validReq($pass, 'pass');
  validReq($pass_re, 'pass_re');
  validPassLength($pass, 'pass');
  unmatchPass($pass, $pass_re, 'pass_re');

  if(empty($error_msg)){
    debug('バリデ通過');

    try {
      $dbh = dbConnect();
      $sql = 'UPDATE users SET password = :pass WHERE id = :u_id';
      $data = array(':pass' => password_hash($pass, PASSWORD_DEFAULT), ':u_id' => $u_id);

      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        debug('上書き成功');
        $_SESSION['msg'] = SUC02;
        header('location: mypage.php');
        exit();
      }else{
        debug('クエリ失敗');
      }
    } catch (Exception $e) {
      error_log('エラー発生：'.$e->getMessage());
      $error_msg['common'] = MSG06;
    }
  }
}

debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');

?>

  <?php
    $sitetitle = 'パスワード変更';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">パスワード変更</h2>
            <p class="error_msg mgt50">
              <?php if(isset($error_msg['common'])) echo $error_msg['common']; ?>
            </p>
            <label>
              新しいパスワード<span class="require_form">必須</span>&nbsp;<span class="require_form">6文字</span>
              <input class="valid_email" type="password" name="pass" value="<?php if(isset($pass)) echo $pass; ?>">
            </label>
            <p class="error_msg"><?php if(isset($error_msg['pass'])) echo $error_msg['pass']; ?></p>
            <label>
              新しいパスワード（確認）<span class="require_form">必須</span>&nbsp;<span class="require_form">6文字</span>
              <input class="valid_email" type="password" name="pass_re" value="<?php if(isset($pass_re)) echo $pass_re; ?>">
            </label>
            <p class="error_msg"><?php if(isset($error_msg['pass_re'])) echo $error_msg['pass_re']; ?></p>
            <input type="submit" class="login_submit" value="パスワード変更">
          </form>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>