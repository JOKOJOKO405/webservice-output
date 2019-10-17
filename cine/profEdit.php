<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= ユーザー登録内容変更ページ');
debug('======================================');
debug(debugLogStart());

$u_id = $_SESSION['user_id'];
$dbHoldData = getUser($u_id);

if(!empty($_POST)){
  debug('ポスト送信がありました');

  $name = $_POST['name'];
  $email = $_POST['email'];
  $profile = $_POST['profile'];
  $icon = (!empty($_FILES['icon']['name'])) ? uploadPoster($_FILES['icon'], 'icon') : '';
  $icon = (empty($_FILES['icon']['name']) && !empty($dbHoldData['icon'])) ? $dbHoldData['icon'] : $icon;

  if(!isset($dbHoldData)){
    validReq($name, 'name');
    validReq($email, 'email');
    validEmail($email, 'email');
  }else{
    if($name !== $dbHoldData['name']){
      validReq($name, 'name');
    }
    if($email !== $dbHoldData['email']){
      validReq($email, 'email');
      validEmail($email, 'email');
    }
  }

  if(empty($error_msg)){
    debug('バリデ通過');

    try {
      $dbh = dbConnect();
      $sql = 'UPDATE users SET email = :email,name = :name,icon = :icon,profile = :profile WHERE id = :id';
      $data = array(':email' => $email, ':name' => $name, ':icon' => $icon, ':profile' => $profile, ':id' => $u_id);

      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        debug('クエリ成功');
        $_SESSION['msg'] = SUC01;
        header('location: mypage.php');
        exit();
      }else{
        debug('クエリ失敗：'.print_r($stmt->errorInfo(),true));
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
  $sitetitle = 'プロフィール編集';
  require('head.php');
?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="filmRegistForm" action="" method="post" enctype="multipart/form-data">
            <h2 class="login_h2">プロフィール編集</h2>
            <p class="error_msg mgt40">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
            <div class="mgt40">
              <div class="filmImg">
                <img src="<?php echo imgDefault(); ?>" alt="" class="live-prev">
                <label class="fileUpload mgt20">
                  アイコン変更
                  <input type="file" name="icon" class="input-files">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['poster'])) echo $error_msg['poster']; ?></p>
              </div>
              <div class="filmAbout">
                <label>
                  ニックネーム&nbsp;<span class="require_form">必須</span>
                  <input type="text" name="name" value="<?php echo holdDBdata('name'); ?>">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['name'])) echo $error_msg['name']; ?></p>
                <label class="mgt20">
                  Email&nbsp;<span class="require_form">必須</span>
                  <input type="text" name="email" value="<?php echo holdDBdata('email'); ?>">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['email'])) echo $error_msg['email']; ?></p>
              </div>
            </div>
            <p class="mgt40">プロフィール&nbsp;<span class="require_form">500文字以内</span></p>
            <textarea name="profile" id="" cols="30" rows="10"><?php echo holdDBdata('profile'); ?></textarea>
            <p class="error_msg"><?php if(isset($error_msg['profile'])) echo $error_msg['profile']; ?></p>
            <input type="submit" class="login_submit" value="変更内容を保存する">
          </form>
        </div>
      </section>
    </main>
    <?php require('footer.php'); ?>
  </body>
</html>
