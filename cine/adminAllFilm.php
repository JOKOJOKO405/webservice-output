<?php
  require('messages.php');
  require('function.php');
  require('session_auth_admin.php');

  $cinemaInfo = getDetail();
  
?>

<?php
    $sitetitle = '映画情報一覧';
    require('head.php');
   ?>
  <body>
    <?php require('headerAD.php'); ?>
    <main>
      <section>
        <div class="shortWrapper">
          <h2>映画情報一覧</h2>
          <table class="allFilm mgt40">
            <?php foreach ($cinemaInfo as $key => $value) { ?>
            <tr>
              <th><?php echo $value['name']; ?></th>
              <td>
                <a href="<?php echo 'adminEditFilm.php?c_id='.$value['id']; ?>" class="filmEditBtn">修正する</a>
              </td>
              <td>
                <a href="<?php echo 'adminDelFilm.php?c_id='.$value['id']; ?>" class="filmDelBtn">削除する</a>
              </td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </section>
    </main>
    <?php require('footerAD.php'); ?>
  </body>
</html>