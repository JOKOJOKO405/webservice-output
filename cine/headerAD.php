<header>
  <div class="admin-top-header">
    <ul>
      <li><a href="adminRegister.php">管理者登録</a></li>
      <?php
      if(!empty($_SESSION['ad_id'])){ ?>
      <li>
        <a href="logout_admin.php">ログアウト</a>
      </li>
      <?php }else{ ?>
      <li>
        <a href="login_admin.php">ログイン</a>
      </li>
      <?php } ?>
      <li>
        <a href="adminMenu.php">管理者メニューページ</a>
      </li>
    </ul>
  </div>
  <div class="admin_header">
    管理者メニューページ<?php if(!empty($_SESSION['ad_id'])) echo '：'.getAdName().'さんログイン中'; ?>
  </div>
</header>