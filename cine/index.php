<?php
  require('messages.php');
  require('function.php');

  $cinemaInfo = getDetail();
  $getCountry = getCountry();
  $getCategory = getCategory();
  
?>

<?php
    $sitetitle = '全ての映画';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="top"></div>
        <div class="wrapper">
          <h2>全ての映画</h2>
          <ul class="lineupIndex mgt60">
            <?php foreach ($cinemaInfo as $key => $value) { ?>
            <li>
              <a href="<?php echo 'detail.php?c_id='.$value['id']; ?>">
                <div class="imgWrap">
                  <img src="<?php echo $value['poster']; ?>" alt="">
                </div>
                <p class="mvTitle"><?php echo $value['name']; ?></p>
              </a>
            </li>
            <?php } ?>
          </ul>
          <a class="login_submit mgt10" href="all.php">もっと見る</a>
        </div>
      </section>
      <section class="bgwhite">
        <div class="wrapper">
          <h2>映画を探す</h2>
          <dl class="searchTitle mgt40">
            <dt>製作国</dt>
            <dd>
              <ul>
                <?php foreach ($getCountry as $key => $val) { ?>
                  <li><a href=""><?php echo $val['name']; ?></a></li>
                <?php } ?>
              </ul>
            </dd>
          </dl>
          <dl class="searchTitle mgt40">
            <dt>ジャンル</dt>
            <dd>
              <ul>
                <?php foreach ($getCategory as $key => $val) { ?>
                  <li><a href=""><?php echo $val['name']; ?></a></li>
                <?php } ?>
              </ul>
            </dd>
          </dl>
        </div>
      </section>
      <section>
        <div class="wrapper">
          <h2>今すぐメンバー登録！</h2>
          <p class="mgt20 txt-cent">
            メンバー登録して、早速レビューを投稿しよう！
          </p>
          <a href="signup.php" class="login_submit">メンバー登録</a>
        </div>
      </section>
    </main>
    <?php require('footer.php'); ?>
  </body>
</html>