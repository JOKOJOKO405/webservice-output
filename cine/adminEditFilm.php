<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= 映画情報登録ページ');
debug('======================================');
debug(debugLogStart());

$c_id = $_GET['c_id'];
$dbHoldData = getDetailOne($c_id);

if(empty($c_id)){
  debug('編集情報がありません。管理者メニューへ遷移します。');
  header('location: adminMenu.php');
}


$dbCategory = getCategory();
$dbcountry = getCountry();

if(!empty($_POST)){
  debug('ポスト送信あり');
  debug('ポスト情報'.print_r($_POST,true));
  debug('ファイル情報'.print_r($_FILES,true));

  $name = $_POST['name'];
  $poster = (!empty($_FILES['poster']['name'])) ? uploadPoster($_FILES['poster'], 'poster') : '';
  $poster = (empty($_FILES['poster']['name']) && !empty($dbHoldData['poster'])) ? $dbHoldData['poster'] : $poster;
  $released = $_POST['released'];
  $country = $_POST['country'];
  $category = $_POST['category'];
  $director = $_POST['director'];
  $prot = $_POST['prot'];
  $editor = getAdName();

  if(!isset($dbHoldData)){

    validReq($name, 'name');
    validReq($released, 'released');
    validReq($country, 'country');
    validReq($category, 'category');
    validReq($director, 'director');
    validReq($prot, 'prot');

  }else{
    if($name !== $dbHoldData['name']){
      validReq($name, 'name');
    }
    if($released !== $dbHoldData['released']){
      validReq($name, 'name');
    }
    if($country !== $dbHoldData['country']){
      validReq($country, 'country');
    }
    if($category !== $dbHoldData['category']){
      validReq($category, 'category');
    }
    if($director !== $dbHoldData['director']){
      validReq($director, 'director');
    }
    if($prot !== $dbHoldData['prot']){
      validReq($prot, 'prot');
    }
  }
  if(empty($error_msg)){
    debug('エラーなし');

    try{
      debug('登録映画情報の上書きをします');
      $dbh = dbConnect();
      $sql = 'UPDATE cinema SET name = :name, category_id = :category, poster = :poster, released = :released, country_id = :country, director = :director, prot = :prot, editor = :editor WHERE id = :id';
      $data = array(':name' => $name, ':category' => $category, ':poster' => $poster, ':released' => $released, ':country' => $country, ':director' => $director, ':prot' => $prot, ':editor' => $editor, ':id' => $c_id);

      debug('上書きされるデータ：'.print_r($data,true));

      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        debug('更新完了');
        $_SESSION['msg'] = adSUC04;
        header('location: adminMenu.php');
        exit();
      }else{
        debug('クエリ失敗');
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
  $sitetitle = '映画情報登録';
  require('head.php');
?>
  <body>
    <?php require('headerAD.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <form class="filmRegistForm" action="" method="post" enctype="multipart/form-data">
            <h2 class="login_h2">映画情報編集</h2>
            <p class="error_msg mgt40">
              <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
            </p>
            <div class="mgt40">
              <div class="filmImg">
                <img src="<?php echo holdDBdata('poster'); ?>" alt="" class="live-prev">
                <label class="fileUpload mgt20">
                  画像選択
                  <input type="file" name="poster" class="input-files">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['poster'])) echo $error_msg['poster']; ?></p>
              </div>
              <div class="filmAbout">
                <label>
                  作品タイトル
                  <input type="text" name="name" value="<?php echo holdDBdata('name'); ?>">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['name'])) echo $error_msg['name']; ?></p>
                <label class="mgt20">
                  上映日
                  <input type="date" name="released" value="<?php echo holdDBdata('released'); ?>">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['released'])) echo $error_msg['released']; ?></p>
                <label class="mgt20">
                  制作国
                  <select name="country">
                    <option value="0" <?php if(holdDBdata('country_id') === 0) echo 'selected'; ?>>選択してください</option>
                    <?php foreach ($dbcountry as $key => $value) {?> 
                      <option value="<?php echo $value['id']; ?>" <?php if(holdDBdata('country_id') === $value['id']) echo 'selected';?> >
                        <?php echo $value['name']; ?>
                      </option>
                    <?php } ?> 
                  </select>
                </label>
                <p class="error_msg"><?php if(isset($error_msg['country'])) echo $error_msg['country']; ?></p>
                <label class="mgt20">
                  カテゴリー
                  <select name="category">
                    <option value="0" <?php if(holdDBdata('category_id') === 0) echo 'selected'; ?> >選択してください</option>
                    <?php foreach ($dbCategory as $key => $value) {?> 
                      <option value="<?php echo $value['id']; ?>" <?php if(holdDBdata('category_id') === $value['id']) echo 'selected'; ?>>
                        <?php echo $value['name']; ?>
                      </option>
                    <?php } ?> 
                  </select>
                </label>
                <p class="error_msg"><?php if(isset($error_msg['category'])) echo $error_msg['category']; ?></p>
                <label class="mgt20">
                  監督
                  <input type="text" name="director" value="<?php echo holdDBdata('director'); ?>">
                </label>
                <p class="error_msg"><?php if(isset($error_msg['director'])) echo $error_msg['director']; ?></p>
              </div>
            </div>
            <p class="mgt20">あらすじ</p>
            <textarea name="prot" id="" cols="30" rows="10"><?php echo holdDBdata('prot'); ?></textarea>
            <p class="error_msg"><?php if(isset($error_msg['prot'])) echo $error_msg['prot']; ?></p>
            <input type="submit" class="login_submit admin_submit" value="登録する">
          </form>
        </div>
      </section>
    </main>
    <?php require('footerAD.php'); ?>
  </body>
</html>
