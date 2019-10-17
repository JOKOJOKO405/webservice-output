<?php

require('function.php');
require('session_auth.php');

debug('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');
debug('＝　会員登録完了画面');
debug('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');

debugLogStart();

?>

<!DOCTYPE html>
<html lang="ja">
  <?php
    $sitetitle = '会員登録完了';
    require('head.php');
  ?>
  <body>
    <?php require('header.php'); ?>
    <main>
      <section>
        <div class="wrapper">
          <h2 class="login_h2">メンバー登録完了</h2>
          <p class="txt-cent mgt50">
            メンバー登録が完了しました。<br>
            マイページよりプロフィール編集を行ってください。
          </p>
          <a href="profEdit.php" class="common_btn">マイページへ</a>
        </div>
      </section>
    </main>

    <?php require('footer.php'); ?>
  </body>
</html>
