<?php

require('messages.php');
require('function.php');
require('session_auth.php');

debug('======================================');
debug('= ログアウトページ');
debug('======================================');
debug(debugLogStart());

session_destroy();

debug('ログアウトしました。indexページへ遷移します。');
header('location:login.php');
exit();