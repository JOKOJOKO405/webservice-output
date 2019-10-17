<?php

require('messages.php');
require('function.php');
require('session_auth_admin.php');

debug('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');
debug('＝　管理者メニューページ');
debug('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');

debugLogStart();


?>

<!DOCTYPE html>
<html lang="ja">
  <?php
    $sitetitle = '管理者メニューページ';
    require('head.php');
  ?>
  <body>
    <?php require('headerAD.php'); ?>
    <p class="msg-suc"><?php echo successMsg('msg'); ?></p>
    <main>
      <section>
        <div class="shortWrapper">
          <h2>管理者メニュー</h2>
          <ul class="mgt40 adminMenuList">
            <li>
              <a href="adminProfEdit.php">
                <img src="img/icon_ad.svg" alt="管理者情報編集">
                管理者情報編集
              </a>
            </li>
            <li>
              <a href="adminRegistFilm.php">
                <img src="img/cinema.svg" alt="映画情報追加">
                映画情報追加
              </a>
            </li>
            <li>
              <a href="adminAllFilm.php">
                <img src="img/edit.svg" alt="映画情報編集">
                映画情報編集
              </a>
            </li>
          </ul>
        </div>
      </section>
    </main>
    <?php require('footerAD.php'); ?>
  </body>
</html>
