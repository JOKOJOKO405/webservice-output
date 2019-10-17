<?php

require('messages.php');
require('function.php');

debug('======================================');
debug('= 管理者登録ページ');
debug('======================================');
debug(debugLogStart());

if(!empty($_POST)){
  debug('管理者登録に入力がありました');

  $name = $_POST['name'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  validReq($name, 'name');
  validReq($pass, 'pass');
  validReq($pass_re, 'pass_re');

  if(empty($error_msg)){
    debug('未入力なし');

    validHalf($pass, 'pass');
    validPassLength($pass, 'pass');
    unmatchPass($pass, $pass_re, 'pass');

    if(empty($error_msg)){
      debug('バリデ通過');
      try{
        $dbh = dbConnect();
        $sql = 'INSERT INTO `admin`(name,password,create_date,update_date,login_date) VALUES(:name,:password,:create_date,:update_date,:login_date) ';
        $data = array(
          ':name' => $name, ':password' => password_hash($pass, PASSWORD_DEFAULT), ':create_date' => date('Y-m-d H:i:s'), ':update_date' => date('Y-m-d H:i:s'), ':login_date' => date('Y-m-d H:i:s')
        );

        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
          $_SESSION['loginAD_date'] = time();

          $setLimit = 60 * 60;
          $setDate = 24;

          $_SESSION['loginAD_limit'] = $setLimit * $setDate;
          
          // ユーザーidをレコードのidとして格納
          $_SESSION['ad_id'] = $dbh->lastInsertId();
        }

        header('location: adminMenu.php');
        exit();
      }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        global $error_msg;
        $error_msg['common'] = MSG06;
      }
    }
  }
}

debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>

<?php
  $sitetitle = '管理者登録';
  require('head.php');
?>
  <body>
    <?php require('headerAD.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">管理者登録</h2>
            <p class="error_msg">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
              <label>
                名前<span class="require_form">必須</span>
                <input class="valid_email" type="text" name="name" value="<?php if(isset($name)) echo $name; ?>">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['name'])) echo $error_msg['name']; ?></p>
              <label>
                パスワード<span class="require_form">必須</span>
                <input class="valid_area" type="password" name="pass" value="">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass'])) echo $error_msg['pass']; ?></p>
              <label>
                パスワード（確認）<span class="require_form">必須</span>
                <input type="password" name="pass_re" value="">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass_re'])) echo $error_msg['pass_re']; ?></p>
            <input type="submit" class="login_submit admin_submit" value="登録する">
          </form>
        </div>
      </section>
    </main>
    <?php require('footerAD.php'); ?>
  </body>
</html>
