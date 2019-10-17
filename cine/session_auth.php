<?php

 if(isset($_SESSION['login_date'])){
   debug('ログイン済ユーザー');

   if($_SESSION['login_date'] + $_SESSION['login_limit'] < time()){

    debug('ログイン有効期限切れ');
    session_destroy();
    
    debug('セッション削除しました');
    debug('ログインページへ遷移します');
    header('location:login.php');
    exit();
   
   }else{
     debug('ログイン有効期限内');
     
     if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('マイページへ遷移します');
      header('location:mypage.php');
      exit();
     }
   }
 }else{
   debug('ログインされてません');
   if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
     header('location: login.php');
     exit();
   }
 }