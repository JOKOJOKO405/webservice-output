
<header>
  <div class="top-header">
    <ul>
      <?php
      if(empty($_SESSION['user_id'])){ ?>
      <li><a href="signup.php">メンバー登録</a></li>
      <?php } ?>
      <?php
      if(!empty($_SESSION['user_id'])){ ?>
      <li>
        <a href="logout.php">ログアウト</a>
      </li>
      <?php }else{ ?>
      <li>
        <a href="login.php">ログイン</a>
      </li>
      <?php } ?>
      <li>
        <a href="login.php">マイページ</a>
      </li>
    </ul>
  </div>
  <div class="main-header">
    <a href="index.php"><img src="img/logo.svg" alt="cinemarks" class="logo"></a>
  </div>
</header>