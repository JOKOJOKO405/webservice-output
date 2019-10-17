<?php
  require('messages.php');
  require('function.php');

  $g_id = $_GET['g_id'];
  $getGenre = getDetailGenre($g_id);
  $nameG = getGenreName($g_id);
?>

<?php
    $sitetitle = 'おすすめ映画';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <h2><?php echo $nameG['name']; ?>のおすすめ作品</h2>
          <div class="mainContentsWrapper cfx">
            <div class="mainContents">
              <p class="txt-cent"><?php if(empty($getGenre)) echo 'まだ何もありません'; ?></p>
              <ul class="lineup">
                <?php foreach ($getGenre as $key => $value) { ?>
                  <li>
                    <a href="detail.php?c_id=<?php echo $value['id']; ?>">
                      <div class="imgWrap">
                        <img src="<?php echo $value['poster']; ?>" alt="">
                      </div>
                      <p class="mvTitle"><?php echo $value['name']; ?></p>
                    </a>
                  </li>
                <?php }?>
              </ul>
            </div>
            <?php require('sidebar.php'); ?>
          </div>
        </div>
      </section>
    </main>
    <?php require('footer.php'); ?>
  </body>
</html>