<?php

require('messages.php');
require('function.php');

debug('======================================');
debug('= パスワード再発行ページ');
debug('======================================');
debug(debugLogStart());

if(!empty($_POST)){

  $email = $_POST['email'];

  validReq($email, 'email');
  validEmail($email, 'email');

  if(!empty($error_msg)){
    debug('バリデ通過');

    try {
      $dbh = dnConnect();
      $sql = 'SELECT count(*) FROM users WHERE email = :email';
      $data = array(':email' => $email);

      $result = queryPost($dbh, $sql, $data);

      debug('クエリ結果：'.print_r($result,true));

      if($result === array_shift($result) ){
        debug('email一致');

        $to = $email;
        $from = 'info@cinemarks.com';
        $subject = 'パスワード認証キーを発行しました';
        $passKey = makeCertifyKey();
        $message = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。
      
パスワード再発行認証キー入力ページ：http://localhost:8888/cine/passRemindPassive.php
認証キー：{$passKey}
※認証キーの有効期限は30分となります
      
認証キーを再発行されたい場合は下記ページより再度再発行をお願い致します。
http://localhost:8888/cine/passRemind.php
      
****************************************
シネマークスカスタマーセンター
URL  http://cinemarks.com/
E-mail info@cinemarks.com
****************************************
EOT;

        $_SESSION['auth_key'] = $passKey;
        $_SESSION['auth_key_limit'] = time()*60*30;
        $_SESSION['auth_email'] = $email;

        sendMail($to, $from, $subject, $message);

        header('location: passRemindPassive.php');
        exit();
      }else{
        debug('クエリ失敗：'.print_r($result->errorInfo(),true));
      }
    } catch (Exception $e) {
      error_log('エラー発生'.$e->getMessage());
      $error_msg['common'] = MSG06;
    }

  }
}

debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');

?>

  <?php
    $sitetitle = 'パスワードの再設定';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">パスワードの再設定</h2>
            <p class="mgt40">
              登録したメールアドレスを入力して、送信ボタンを押して下さい。
            </p>
            <p class="error_msg mgt50">
              <?php if(isset($error_msg['common'])) echo $error_msg['common']; ?>
            </p>
            <label>
              登録したメールアドレス<span class="require_form">必須</span>
              <input class="valid_email" type="text" name="email" value="<?php if(isset($email)) echo $email; ?>">
            </label>
            <p class="error_msg"><?php if(isset($error_msg['email'])) echo $error_msg['email']; ?></p>
            <input type="submit" class="login_submit" value="送信">
          </form>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>
