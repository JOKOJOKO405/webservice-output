<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= 管理者ログインページ');
debug('======================================');
debug(debugLogStart());

if(!empty($_POST)){
  
  $name = $_POST['name'];
  $pass = $_POST['pass'];

  validReq($name, 'name');
  validReq($pass, 'pass');

  debug('未入力チェックOK');

  if(empty($error_msg)){

    validPassLength($pass, 'pass');


    if(empty($error_msg)){
      debug('バリデ通過');

      try{

        $dbh = dbConnect();
        $sql = 'SELECT password,id FROM `admin` WHERE `name` = :name';
        $data = array(':name' => $name);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        

        debug('クエリ結果の中身：'.print_r($result,true));

        if(!empty($result) && password_verify($pass, array_shift($result))){
          debug('パスワード一致');

          $_SESSION['loginAD_date'] = time();

          $setLimit = 60 * 60;
          $setDate = 24;

          $_SESSION['loginAD_limit'] = $setLimit * $setDate;
          
          // ユーザーidをレコードのidとして格納
          $_SESSION['ad_id'] = $result['id'];

          debug('セッション変数の中身：'.print_r($_SESSION,true));
          debug('管理者メニューページへ遷移します');
          header('location:adminMenu.php');
          exit();

        }else{
          debug('パスワードが一致しません');
          $error_msg['pass'] = adMSG01;
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
    <?php require('headerAD.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="form_login" action="" method="post">
            <h2 class="login_h2">管理者ログイン</h2>
            <p class="error_msg">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
              <label>
                名前<span class="require_form">必須</span>
                <input class="valid_email" type="text" name="name" value="<?php if(isset($name)) echo $name; ?>">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['name'])) echo $error_msg['name']; ?></p>
              <label>
                パスワード<span class="require_form">必須</span>&nbsp;<span class="require_form">6文字以上</span>
                <input class="valid_area" type="password" name="pass" value="">
              </label>
              <p class="error_msg"><?php if(isset($error_msg['pass'])) echo $error_msg['pass']; ?></p>
            <input type="submit" class="admin_submit" value="ログイン">
          </form>
        </div>
      </section>
    </main>

    <?php require('footerAD.php'); ?>
  </body>
</html>
