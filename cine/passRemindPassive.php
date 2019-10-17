<?php

require('messages.php');
require('function.php');

debug('======================================');
debug('= パスワード再発行認証キー入力ページ');
debug('======================================');
debug(debugLogStart());

if(!isset($_SESSION['auth_key'])){
  debug('セッションログイン認証キー期限切れです');
  header('location: login.php');
}

if(!empty($_POST)){
  debug('ポスト送信がありました');

  $passKey = $_POST['passKey'];

  validReq($passKey, 'passKey');
  validHalf($passKey, 'passKey');

  if(empty($error_msg)){
    debug('バリデ通過');

    if($passKey !== $_SESSION['auth_key']){
      debug('キーが一致しません');
      $error_msg['common'] = MSG09;
    }
    if(time() > $_SESSION['auth_key_limit']){
      debug('認証キー有効期限切れ');
      $error_msg = MSG10;
    }

    if(empty($error_msg)){
      debug('認証キーバリデ通過');

      $newPw = makeCertifyKey();

      try{
        $dbh = dbConnect();
        $sql = 'UPDATE users SET password = :pass WHERE email = :email';
        $data = array(':pass' => $newPw, ':email' => $_SESSION['auth_email']);

        $result = queryPost($dbh, $sql, $data);

        if($result){
          debug('クエリ成功'.print_r($result,true));

          $to = $_SESSION['auth_email'];
          $from = 'info@cinemarks.com';
          $subject = 'パスワードを再発行しました';
          $message = <<<EOT
本メールアドレス宛にパスワードの再発行を致しました。
下記のURLにて再発行パスワードをご入力頂き、ログインください。
          
ログインページ：http://localhost:8888/cine/login.php
再発行パスワード：{$newPw}
※ログイン後、マイページよりパスワードのご変更をお願い致します。

****************************************
シネマークスカスタマーセンター
URL  http://cinemarks.com/
E-mail info@cinemarks.com
****************************************
EOT;
          
          sendMail($to, $from, $subject, $message);

          session_unset();

          header('location: login.php');
          exit();
        }else{
          debug('クエリ失敗'.print_r($result->errorInfo(),true));
          $err_msg['common'] = MSG06;
        }
      }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $error_msg['common'] = MSG06;
      }
    }
  }
}

debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');

?>

  <?php
    $sitetitle = '認証キー入力ページ';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">パスワード認証キー入力</h2>
            <p class="mgt40">
              Emailに記載されている認証キーを入力してください。（認証キー有効期限はメール受信より30分です。）
            </p>
            <p class="error_msg mgt50">
              <?php if(isset($error_msg['common'])) echo $error_msg['common']; ?>
            </p>
            <label>
              認証キー<span class="require_form">必須</span>
              <input type="text" name="passKey" value="<?php if(isset($passKey)) echo $passKey; ?>">
            </label>
            <p class="error_msg"><?php if(isset($error_msg['passKey'])) echo $error_msg['passKey']; ?></p>
            <input type="submit" class="login_submit" value="送信">
            <p class="passRemind mgt10"><a href="passRemind.php">認証キーの再発行はこちら</a></p>
          </form>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>
