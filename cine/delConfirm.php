<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= 登録情報削除依頼画面');
debug('======================================');
debug(debugLogStart());


debug('画面処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');

?>

  <?php
    $sitetitle = '登録情報削除';
    require('head.php');
   ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <div class="form_login">
            <h2 class="login_h2">登録情報削除</h2>
            <p class="mgt30 txt-cent">
              Cinemarksを退会してもよろしいですか？<br>
              これまでの記録はすべて削除されてしまいます。
            </p>
            <a href="userDelProf.php" class="login_submit">退会する</a>
          </div>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>
