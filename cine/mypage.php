<?php
  require('messages.php');
  require('function.php');
  require('session_auth.php');

  debug('======================================');
  debug('= マイページ');
  debug('======================================');
  debug(debugLogStart());

  $u_id = $_SESSION['user_id'];

  // レビューユーザー
  $dbHoldData = getTimeline($u_id);
  debug('データベース中身：'.print_r($dbHoldData,true));

  $dbFavourite = getFavourite($u_id);
  debug('お気に入り情報：'.print_r($dbFavourite,true));

?>

<?php
    $sitetitle = 'マイページ';
    require('head.php');
   ?>
  <body>
    <div class="overWrap"></div>
    <?php require('header.php'); ?>
    <p class="msg-suc"><?php echo successMsg('msg'); ?></p>
    <main>
      <section>
        <div class="wrapper">
          <h1 class="txt-cent">マイページ</h1>
          <p class="error_msg txt-cent mgb30">
            <?php if(isset($error_msg['common'])) echo $error_msg['common'] ?>
          </p>
          <div class="mainContentsWrapper cfx">
            <div class="mainContents">
              <div class="mypReview">
                <h2 class="mgb50">最近レビューした映画</h2>
                <p class="txt-cent"><?php if(empty($dbHoldData)) echo 'まだレビューはありません'; ?></p>
                <?php foreach ($dbHoldData as $key => $val) { ?>
                <div class="detailWrap cfx">
                  <div class="mypDetailImg">
                    <a href="<?php echo 'detail.php?c_id='.$val['cinema_id']; ?>">
                      <img src="<?php echo $val['poster']; ?>" alt="">
                    </a>
                  </div>
                  <div class="mypDetailInfo">
                    <a href="<?php echo 'detail.php?c_id='.$val['cinema_id']; ?>">
                      <h2 class="mypDetailTitle"><?php echo $val['name']; ?></h2>
                    </a>
                    <p class="detailotherInfo mgt10">
                      レビュー投稿日：<?php echo $val['create_date']; ?>
                    </p>
                    <div class="detailotherInfo mgt10">
                      <p class="fonB">レビューした内容</p>
                      <p class="mgt05"><?php echo $val['review']; ?></p>
                    </div>
                  </div>
                </div>
                <hr class="myP">
                <?php } ?>
              <!-- レビュー終わり -->
              </div>
              <div class="userVoiceWrap mgt50">
              <div class="userVoice">
                <h2 class="mgb50">お気に入り</h2>
                <p class="txt-cent"><?php if(empty($dbFavourite)) echo 'まだ何もありません'; ?></p>
                <ul class="favouriteList mgt40">
                  <?php foreach ($dbFavourite as $key => $val) { ?>
                    <li>
                      <a href="detail.php?c_id=<?php echo $val['cinema_id']; ?>">
                        <div class="favImgBox"><img src="<?php echo $val['poster']; ?>" alt=""></div>
                        <p class="favTitle mgt10"><?php echo $val['name']; ?></p>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <!-- maincontents終わり -->
            </div>
            <?php require('sidebar.php'); ?>
          </div>
        </div>
      </section>
    </main>
    <?php require('footer.php'); ?>
  </body>
</html>