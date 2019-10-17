<?php

require('function.php');
require('session_auth_admin.php');

debug('======================================');
debug('= ログアウトページ');
debug('======================================');
debug(debugLogStart());

session_destroy();

debug('ログアウトしました。管理ログインページへ遷移します。');
header('location:login_admin.php');
exit();