<?php

 if(isset($_SESSION['loginAD_date'])){
   debug('ログイン済管理者');

   if($_SESSION['loginAD_date'] + $_SESSION['loginAD_limit'] < time()){

    debug('ログイン有効期限切れ');
    session_destroy();
    
    debug('セッション削除しました');
    debug('ログインページへ遷移します');
    header('location:login_admin.php');
    exit();
   
   }else{
     debug('ログイン有効期限内');
     
     if(basename($_SERVER['PHP_SELF']) === 'login_admin.php'){
      debug('管理者メニューページへ遷移します');
      header('location:adminMenu.php');
      exit();
     }
   }
 }else{
   debug('ログインされてません');
   if(basename($_SERVER['PHP_SELF']) !== 'login_admin.php'){
     header('location: login_admin.php');
     exit();
   }
 }