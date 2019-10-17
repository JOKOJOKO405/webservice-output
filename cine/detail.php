<?php
  require('messages.php');
  require('function.php');

  $c_id = $_GET['c_id'];
  $u_id = $_SESSION['user_id'];
  $cinemaInfo = getDetailOne($c_id);
  debug('シネマインフォ：'.print_r($cinemaInfo,true));
  
  // レビュー表示変数
  $showReview = getReview($c_id);
  debug('レビュー表示：'.print_r($showReview,true));

  // レビューユーザー
  $dbHoldData = showReview($c_id, $u_id);
  $dbComment = holdDBdata('review');


  if(!empty($_POST)){
    $review = $_POST['review'];

    validReq($review, 'review');
    validTextMax($review, 'review');

    if(empty($error_msg)){
      debug('バリデ通過');

      if(empty($dbComment)){
        try {
          debug('レビューデータ挿入');

          $dbh = dbConnect();
          $sql = 'INSERT INTO review (user_id,cinema_id,review,create_date,update_date) VALUES(:u_id,:c_id,:review,:create_date,:update_date)';
          $data = array(':u_id' => $u_id, ':c_id' => $c_id, ':review' => $review, ':create_date' => date('Y-m-d H:i:s'), ':update_date' => date('Y-m-d H:i:s'));

          $stmt = queryPost($dbh, $sql, $data);
          if($stmt){
            debug('レビュー書き込み成功');
            $_SESSION['msg'] = SUC03;
            header('location: detail.php?c_id='.$c_id);
            exit();
          }

        } catch (Exception $e) {
          error_log('エラー発生：'.$e->getMessage());
          $error_msg['common'] = MSG06;
        }
      }else{
        if(isset($dbHoldData) && $dbComment !== $review){
          debug('レビュー編集です');

          validReq($review, 'review');
          validTextMax($review, 'review');

          if(empty($error_msg)){
            try {
              $dbh = dbConnect();
              $sql = 'UPDATE review SET review = :review, update_date = :update_date WHERE user_id = :u_id AND cinema_id = :c_id';
              $data = array(':review' => $review, ':update_date' => date('Y-m-d H:i:s'), ':u_id' => $u_id, ':c_id' => $c_id);

              $stmt = queryPost($dbh, $sql, $data);

              if($stmt){
                debug('レビュー上書き完了。リロードします');
                $_SESSION['msg'] = SUC04;
                header('location: detail.php?c_id='.$c_id);
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
      }

    }
  }

?>

<?php
    $sitetitle = '詳細';
    require('head.php');
   ?>
  <body>
    <div class="overWrap"></div>
    <?php require('header.php'); ?>
    <p class="msg-suc"><?php echo successMsg('msg'); ?></p>
    <main>
      <section>
        <div class="wrapper">
          <p class="error_msg">
            <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
          </p>
          <div class="mainContentsWrapper cfx">
            <div class="mainContents">
              <div class="detailWrap cfx">
                <div class="detailImg">
                  <img src="<?php echo $cinemaInfo['poster']; ?>" alt="">
                  <div class="btnBox">
                    <button class="markBtn eng">review!</button>
                    <button class="likeBtn js-click-fav <?php if(isLike($u_id, $c_id)){echo 'active';} ?> eng" aria-hidden="true" data-cinemaid="<?php echo convertCharset($cinemaInfo['id']); ?>"><i class="far fa-thumbs-up"></i>&nbsp;like!</button>
                  </div>
                </div>
                <div class="detailInfo">
                  <h2 class="detailTitle"><?php echo $cinemaInfo['name']; ?></h2>
                  <p class="detailotherInfo mgt20">
                    上映日：<?php echo $cinemaInfo['released']; ?> ／ 製作国：<?php echo $cinemaInfo['country']; ?>
                  </p>
                  <p class="detailotherInfo mgt05">
                    ジャンル：<?php echo $cinemaInfo['category']; ?>
                  </p>
                  <h3 class="detailTerm">あらすじ</h3>
                  <p>
                  <?php echo $cinemaInfo['prot']; ?>
                  </p>
                </div>
              </div>
            </div>
            <?php require('sidebar.php'); ?>
          </div>
        </div>
        <div class="userVoiceWrap">
          <div class="userVoice">
            <h2 class="mgb50">みんなの評価</h2>
            <p class="txt-cent"><?php if(empty($showReview)) echo 'まだレビューはありません'; ?></p>
            <?php foreach ($showReview as $key => $val) { ?>
              <dl class="usersColumnBox">
                <dt>
                  <img src="<?php if(empty($val['icon'])){ echo 'img/unknow.jpg';}else{echo $val['icon'];} ?>" alt="">
                </dt>
                <dd>
                  <p class="userName"><?php echo $val['name']; ?></p>
                  <p class="update"><?php echo $val['create_date']; ?></p>
                  <p class="userComment"><?php echo $val['review']; ?></p>
                </dd>
              </dl>
            <hr class="userComment">
            <?php } ?>
          </div>
        </div>
      </section>
    </main>
    <!-- レビューボックス -->
    <div class="reviewBox">
      <h3 class="reviewTitle">レビュー</h3>
      <div class="reviewBoxInner">
        <form action="" method="post">
          <textarea name="review" id="" rows="10" placeholder="500文字以内で入力してください"><?php if(!empty($dbComment)) echo $dbComment; ?></textarea>
          <input type="submit" value="<?php if(empty($dbComment)){ echo '投稿する'; }else{echo '編集する'; } ?>" class="login_submit">
        </form>
      </div>
      <button class="close">×</button>
    </div>
    <?php require('footer.php'); ?>
  </body>
</html>