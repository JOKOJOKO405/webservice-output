<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= ログインページ');
debug('======================================');
debug(debugLogStart());

if(!empty($_POST)){
  
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (isset($_POST['save_pass'])) ? true : false;

  validReq($email, 'email');
  validReq($pass, 'pass');

  debug('未入力チェックOK');

  if(empty($error_msg)){

    validEmail($email, 'email');
    validPassLength($pass, 'pass');


    if(empty($error_msg)){
      debug('バリデ通過');

      try{

        $dbh = dbConnect();
        $sql = 'SELECT password,id FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        

        debug('クエリ結果の中身：'.print_r($result,true));

        if(!empty($result) && password_verify($pass, array_shift($result))){
          debug('パスワード一致');

          $_SESSION['login_date'] = time();

          $setLimit = 60 * 60;
          $setDate = 24 * 14;

          if(isset($_POST['pass_save'])){
            debug('ログイン保持チェックあり');
            $_SESSION['login_limit'] = $setLimit * $setDate;
          }else{
            debug('ログイン保持チェックなし');
            $_SESSION['login_limit'] = $setLimit;
          }
          
          // ユーザーidをレコードのidとして格納
          $_SESSION['user_id'] = $result['id'];

          debug('セッション変数の中身：'.print_r($_SESSION,true));
          debug('マイページへ遷移');
          header('location:mypage.php');
          exit();

        }else{
          debug('パスワードが一致しません');
          $error_msg['pass'] = MSG07;
        }

      }catch(Exception $e){
        debug('エラー発生：'.$e->getMessage());
        $error_msg['common'] = MSG06;
      }
    }

  }
  
}
debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');

?>

  <?php
    $sitetitle = 'ログインページ';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">メンバーログイン</h2>
            <p class="error_msg">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
              <label>
                メールアドレス<span class="require_form">必須</span>
                <input class="valid_email" type="text" name="email" value="<?php if(isset($email)) echo $email; ?>">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['email'])) echo $error_msg['email']; ?></p>
              <label>
                パスワード<span class="require_form">必須</span>
                <input class="valid_area" type="password" name="pass" value="">
              </label>
              <label>
                <input type="checkbox" name="pass_save">&nbsp;ログインしたままにする
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass'])) echo $error_msg['pass']; ?></p>
            <input type="submit" class="login_submit" value="ログイン">
            <p class="passRemind mgt10"><a href="passRemind.php">パスワードを忘れてしまった方はこちら</a></p>
          </form>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>
