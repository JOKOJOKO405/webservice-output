<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= 映画情報登録ページ');
debug('======================================');
debug(debugLogStart());

$dbCategory = getCategory();
$dbcountry = getCountry();
$editor = getAdName();

if(!empty($_POST)){
  debug('データが送信されました');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  $name = $_POST['name'];
  $released = $_POST['released'];
  $poster = uploadPoster($_FILES['poster'], 'poster');
  $country = $_POST['country'];
  $category = $_POST['category'];
  $director = $_POST['director'];
  $prot = $_POST['prot'];

  validReq($name, 'name');
  validReq($released, 'released');
  validReq($poster, 'poster');
  validReq($country, 'country');
  validReq($category, 'category');
  validReq($director, 'director');
  validReq($prot, 'prot');

  if(empty($error_msg)){
    debug('バリデ通過');

    try{
      $dbh = dbConnect();
      $sql = 'INSERT INTO cinema(name,category_id,poster,country_id,director,released,prot,create_date,update_date,editor) VALUES(:name,:category_id,:poster,:country_id,:director,:released,:prot,:create_date,:update_date,:editor)';
      $data = array(
        ':name' => $name, ':category_id' => $category, ':poster' => $poster, ':country_id' => $country,
        ':director' => $director, ':released' => $released, ':prot' => $prot, ':create_date' => date('Y-m-d H:i:s'),
        ':update_date' => date('Y-m-d H:i:s'), ':editor' => $editor
      );
      $result = queryPost($dbh, $sql, $data);

      if($result){
        debug('$result変数の中身：'.print_r($result,true));
        $_SESSION['msg'] = adSUC01;
        header('location:adminMenu.php');
        exit();
      }
      
    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
      global $error_msg;
      $error_msg['common'] = MSG06;
    }
  }
}



debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>

<?php
  $sitetitle = '映画情報登録';
  require('head.php');
?>
  <body>
    <?php require('headerAD.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="filmRegistForm" action="" method="post" enctype="multipart/form-data">
            <h2 class="login_h2">映画情報登録</h2>
            <p class="error_msg mgt40">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
            <div class="mgt40">
              <div class="filmImg">
                <img src="" alt="" class="live-prev">
                <label class="fileUpload mgt20">
                  画像選択
                  <input type="file" name="poster" class="input-files">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['poster'])) echo $error_msg['poster']; ?></p>
              </div>
              <div class="filmAbout">
                <label>
                  作品タイトル
                  <input type="text" name="name" value="<?php if(isset($name)) echo $name; ?>">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['name'])) echo $error_msg['name']; ?></p>
                <label class="mgt20">
                  上映日
                  <input type="date" name="released" value="">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['released'])) echo $error_msg['released']; ?></p>
                <label class="mgt20">
                  制作国
                  <select name="country">
                    <option value="0">選択してください</option>
                    <?php foreach ($dbcountry as $key => $value) {?> 
                      <option value="<?php echo $value['id']; ?>">
                        <?php echo $value['name']; ?>
                      </option>
                    <?php } ?> 
                  </select>
                </label>
                <p class="error_msg"><?php if(isset($error_msg['country'])) echo $error_msg['country']; ?></p>
                <label class="mgt20">
                  カテゴリー
                  <select name="category">
                    <option value="0">選択してください</option>
                    <?php foreach ($dbCategory as $key => $value) {?> 
                      <option value="<?php echo $value['id']; ?>">
                        <?php echo $value['name']; ?>
                      </option>
                    <?php } ?> 
                  </select>
                </label>
                <p class="error_msg"><?php if(isset($error_msg['category'])) echo $error_msg['category']; ?></p>
                <label class="mgt20">
                  監督
                  <input type="text" name="director" value="">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['director'])) echo $error_msg['director']; ?></p>
              </div>
            </div>
            <p class="mgt20">あらすじ</p>
            <textarea name="prot" id="" cols="30" rows="10"></textarea>
            <p class="error_msg"><?php if(isset($error_msg['prot'])) echo $error_msg['prot']; ?></p>
            <input type="submit" class="login_submit admin_submit" value="登録する">
          </form>
        </div>
      </section>
    </main>
    <?php require('footerAD.php'); ?>
  </body>
</html>
