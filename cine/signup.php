<?php

require('messages.php');
require('function.php');

debug('======================================');
debug('= メンバー登録ページ');
debug('======================================');
debug(debugLogStart());



if(!empty($_POST)){
  
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  validReq($email, 'email');
  validReq($pass, 'pass');
  validReq($pass_re, 'pass_re');

  if(empty($error_msg)){

    validPassLength($pass, 'pass');
    unmatchPass($pass, $pass_re, 'pass');

  if(empty($error_msg)){

    EmailDup($email);

  }


  debug('バリデ通過');

  if(empty($error_msg)){
    //例外処理
    try {
      $dbh = dbConnect();
      $sql = 'INSERT INTO users (email,password,create_date,login_date) VALUES(:email,:pass,:create_date,:login_date)';
      $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                    ':create_date' => date('Y-m-d H:i:s'),
                    ':login_date' => date('Y-m-d H:i:s'));

      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      debug('SQL文：'.print_r($stmt->errorInfo(), true));

      if($stmt){
        $_SESSION['user_id'] = $dbh->lastInsertId();

        $setLimit = 60 * 60;
        $setDate = 24 * 14;

        $_SESSION['login_date'] = time();

        $_SESSION['login_limit'] = $setLimit * $setDate;

        debug('セッション変数の中身：'.print_r($_SESSION,true));

        header("Location:complete_signup.php");
        exit(); 
      }else{
        debug('クエリ失敗');
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $error_msg['common'] = MSG06;
    }
  }

  }

  
}
debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>

<?php
  $sitetitle = 'メンバー登録';
  require('head.php');
?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">メンバー登録</h2>
            <p class="error_msg">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
              <label>
                メールアドレス<span class="require_form">必須</span>
                <input class="valid_email" type="text" name="email" value="<?php if(isset($email)) echo $email; ?>">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['email'])) echo $error_msg['email']; ?></p>
              <label>
                パスワード<span class="require_form">必須</span>&nbsp;<span class="require_form">6文字</span>
                <input class="valid_area" type="password" name="pass" value="">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass'])) echo $error_msg['pass']; ?></p>
              <label>
                パスワード（確認）<span class="require_form">必須</span>&nbsp;<span class="require_form">6文字</span>
                <input type="password" name="pass_re" value="">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass_re'])) echo $error_msg['pass_re']; ?></p>
            <input type="submit" class="login_submit" value="登録する">
          </form>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>
