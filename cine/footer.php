<?php
  $dbCategory = getCategory();
  $dbcountry = getCountry();
 ?>

<footer>
      <div class="wrapper">
        <ul class="ftmenu">
          <li>
            <p class="ftr_h4">最新映画</p>
            <ul class="categoryDetail">
              <li>
                <a href="">上映中の映画</a>
              </li>
              <li>
                <a href="">今週公開の映画</a>
              </li>
              <li><a href="">公開予定の映画</a></li>
            </ul>
          </li>
          <li>
            <p class="ftr_h4">ジャンルで探す</p>
            <ul class="categoryDetail genre">
              <?php foreach ($dbCategory as $key => $value) { ?>
                <li>
                  <a href="<?php echo 'genre.php?g_id='.$value['id']; ?>"><?php echo $value['name']; ?></a>
                </li>
              <?php } ?>
            </ul>
          </li>
          <li>
            <p class="ftr_h4">運営会社</p>
            <ul class="categoryDetail">
              <li>
                <a href="">企業情報</a>
              </li>
              <li>
                <a href="">お問い合わせ</a>
              </li>
              <li><a href="">プライバシーポリシー</a></li>
              <li><a href="">利用規約</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="copywrap">
        <small class="eng">&copy;&nbsp;cinemarks</small>
      </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/common.js"></script>
    