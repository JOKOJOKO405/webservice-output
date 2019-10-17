<?php
  require('messages.php');
  require('function.php');

  $cinemaInfo = getDetail();
  
?>

<?php
    $sitetitle = '全ての映画';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <h2>全ての映画</h2>
          <div class="mainContentsWrapper cfx">
            <div class="mainContents">
              <ul class="lineup">
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
            </div>
            <?php require('sidebar.php'); ?>
          </div>
        </div>
      </section>
    </main>
    <?php require('footer.php'); ?>
  </body>
</html>