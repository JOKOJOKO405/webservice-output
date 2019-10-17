<?php
  $u_id = $_SESSION['user_id'];
  $dbHoldData = getUser($u_id);

  $dbCategory = getCategory();
  $dbcountry = getCountry();


 ?>

<div class="sideBar">
  <?php if($u_id){ ?>
    <div class="myProfile">
      <ul class="profWrap">
        <li class="profImg">
          <a href="mypage.php"><img src="<?php echo imgDefault(); ?>" alt=""></a>
        </li>
        <li>
          <p class="u_name"><?php echo holdDBdata('name'); ?>さん</p>
        </li>
      </ul>
      <a href="profEdit.php" class="myPageEditBtn mgt20">マイページ編集</a>
      <a href="passEdit.php" class="myPageEditBtn mgt05">パスワード変更</a>
      <a href="delConfirm.php" class="myPageEditBtn mgt05">退会する</a>
    </div>
  <?php } ?>
  <dl class="searchToggle">
    <dt>最新映画</dt>
    <dd>
      <ul>
        <li>
          <a href="">上映中の映画</a>
        </li>
        <li>
          <a href="">今週公開の映画</a>
        </li>
        <li>
          <a href="">公開予定の映画</a>
        </li>
      </ul>
    </dd>
    <dt>ジャンルで探す</dt>
    <dd>
      <ul>
        <?php foreach ($dbCategory as $key => $value) { ?>
          <li>
            <a href="<?php echo 'genre.php?g_id='.$value['id']; ?>">
              <?php echo $value['name']; ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </dd>
  </dl>
</div>