<?php

ini_set('log_errors', 'on');
ini_set('error_log', 'php_error.php');


// デバッグ
$debug_flg = true;

function debug($str){
  global $debug_flg;

  if(!empty($debug_flg)){
    error_log('デバッグーーー＞'.$str);
  }
}



// セッション
session_save_path('/var/tmp/');
ini_set('session.gc_maxlifetime', 60*60*24*14);
ini_set('session.cookie_lifetime', 60*60*24*14);
session_start();
session_regenerate_id();



function debugLogStart(){
  debug('画面処理開始＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
  debug('セッションID：'.session_id());
  debug('セッション変数：'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ：'.time());
}

// バリデ未入力
function validReq($str, $key){
  if($str === ''){
    global $error_msg;
    $error_msg[$key] = MSG01;
  }
}
// バリデEmail正規表現
function validEmail($str, $key){
  if(!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $str)) {
    global $error_msg;
    $error_msg[$key] = MSG02;
  }
}
// バリデ半角
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
    global $error_msg;
    $error_msg[$key] = MSG08;
  }
}
// バリデパスワード6文字
function validPassLength($str, $key){
  if(mb_strlen($str) < 6){
    global $error_msg;
    $error_msg[$key] = MSG03;
  }
}
// バリデパスワードアンマッチ
function unmatchPass($str1, $str2, $key){
  if($str1 !== $str2){
    global $error_msg;
    $error_msg[$key] = MSG04;
  }
}
// バリデレビュー文字数
function validTextMax($str, $key, $len = 500){
  if(mb_strlen($str) > $len){
    global $error_msg;
    $error_msg[$key] = MSG11;
  }
}
// バリデemail重複
function EmailDup($email){
  global $error_msg;
  try{
    $dbh = dbConnect();
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty(array_shift($result))){
      $error_msg['common'] = MSG05;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
    $error_msg['common'] = MSG06;
  }
}
// バリデサニタイズ
function convertCharset($str){
  return htmlspecialchars($str,ENT_QUOTES);
}
function makeCertifyKey(){
  $keys = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890';
  $str = (int)'';
  for ($i=0; $i < 8; ++$i) { 
    $str .= $keys[mt_rand(0, 61)];
  }
  return $str;
}

function sendMail($to, $From, $subject, $message){

  if(isset($to) && isset($from) && isset($subject) && isset($message)){
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');

    $result = mb_send_mail($to, 'From:'.$from, $subject, $message);

    if($result){
      debug('メール送信成功');
    }else{
      debug('メール送信失敗');
    }
  }
  
}


function dbConnect(){
  $dns = 'mysql:dbname=cinemarks;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  $dbh = new PDO($dns, $user, $password, $options);
  return $dbh;
}

function queryPost($dbh, $sql, $data){
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  return $stmt;
}

// 管理者情報取得
function getAdmin($ad_id){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM admin WHERE id = :ad_id';
    $data = array(':ad_id' => $ad_id);

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt){
      debug('クエリ結果表示：'.print_r($result, true));
      return $result;
    }else{
      debug('クエリ失敗：'.print_r($stmt->errorInfo(),true));
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
  
}

function getUser($u_id){
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM users WHERE id = :id';
    $data = array(':id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt){
      debug('クエリ成功：'.print_r($result, true));
      return $result;
    }else{
      debug('クエリ失敗'.print_r($stmt->errorInfo(),true));
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }
}

// 管理者名前表示
function getAdName(){
  $adminData = getAdmin($_SESSION['ad_id']);
  $adminName = $adminData['name'];
  return $adminName;
}
// カテゴリ取得
function getCategory(){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM `category`';
    $data = array();

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();
    if(!empty($result)){
      return $result;
    }else{
      return false;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
    debug('クエリ失敗しました。');
  }
}
// 制作国取得
function getCountry(){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM `country`';
    $data = array();

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();
    if(!empty($result)){
      return $result;
    }else{
      return false;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
    debug('クエリ失敗しました。');
  }
}
// 画像アップロード
function uploadPoster($file, $key){
  debug('画像がアップロードされました');
  debug('画像情報：'.print_r($file, true));

  if(isset($file['error']) && is_int($file['error'])){
    try{
      switch($file['error']){
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイル未選択');
          break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        default:
          throw new RuntimeException('その他エラー'.errorInfo());
      }
      // 画像形式の割り出し
      $type = @exif_imagetype($file['tmp_name']);
      if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
        throw new RuntimeException('画像形式が未対応です');
      }
      debug('type変数の中身：'.print_r($type,true));

      // ファイルパスの作成
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if(!move_uploaded_file($file['tmp_name'], $path)){
        throw new RuntimeException('保存エラー発生');
        
      }
      debug('path変数の中身：'.print_r($path,true));

      chmod($path, 0644);

      debug('ファイルがアップロードされました');

      return $path;
    }catch(RuntimeException $e){
      debug($e->getMessage());
      global $error_msg;
      $error_msg['poster'] = $e->getMessage();
    }
  }
}

// 映画情報全取得
function getDetail(){
  try{
    $dbh = dbConnect();

    if(basename($_SERVER['PHP_SELF']) === 'all.php'){
      $sql = 'SELECT * FROM cinema WHERE delete_flg = 0';
    }else{
      $sql = 'SELECT * FROM cinema WHERE delete_flg = 0 LIMIT 6';
    }
    $data = array();

    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->fetchAll();

    if($result){
      debug('クエリ結果：'.print_r($result,true));
      return $result;
    }else{
      return false;
    }

    
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}
// ジャンル別ページ表示
function getDetailGenre($g_id){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM cinema WHERE category_id = :category_id AND delete_flg = 0';
    $data = array(':category_id' => $g_id);

    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->fetchAll();

    if($result){
      debug('クエリ結果：'.print_r($result,true));
      return $result;
    }else{
      return false;
    }

    
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

function getGenreName($g_id){
  try {
    $dbh = dbConnect();
    $sql = 'SELECT name FROM category WHERE id = :id';
    $data = array(':id' => $g_id);
    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt){
      return $result;
    }
  } catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}
// 映画情報個別取得
function getDetailOne($c_id){
  debug('映画情報を取得します。');
  debug('シネマID：'.$c_id);
  try{
    $dbh = dbConnect();
    $sql = 'SELECT c.id,c.name,c.category_id,c.poster,c.country_id,c.director,c.released,c.prot,c.create_date,c.update_date,ca.name AS category,co.name AS country FROM cinema AS c LEFT JOIN category AS ca ON c.category_id = ca.id LEFT JOIN country AS co ON c.country_id = co.id WHERE c.id = :c_id AND c.delete_flg = 0 AND ca.delete_flg = 0 AND co.delete_flg = 0';

    $data = array(':c_id' => $c_id);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('変数resultの中身'.print_r($result,true));
      return $result;
    }else{
      debug('クエリ失敗：'.$stmt->errorInfo(),true);
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
  }
}

function holdDBdata($key){
  global $dbHoldData;
  if(isset($dbHoldData[$key])){
    return $dbHoldData[$key];
  }
}
function imgDefault(){
  global $dbHoldData;
  if(empty($dbHoldData['icon'])){
    return 'img/unknow.jpg';
  }else{
    return $dbHoldData['icon'];
  }
}

function showPoster($file, $key){
  if(empty($poster)){
    return uploadPoster($file, $key);
  }else{
    if(isset($dbHoldData[$key])){
      return $dbHoldData[$key];
    }
  }
}
// レビュー取得
function getReview($c_id){
  debug('映画レビューを取得します。');
  debug('シネマID：'.$c_id);
  try{
    $dbh = dbConnect();
    $sql = 'SELECT c.id,re.cinema_id,re.user_id,re.review,re.create_date,u.id,u.name,u.icon FROM cinema AS c JOIN review AS re ON c.id = re.cinema_id RIGHT JOIN users AS u ON u.id = re.user_id WHERE c.id = :c_id AND c.delete_flg = 0 ORDER BY re.create_date';
    $data = array(':c_id' => $c_id);

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();

    if(!empty($result)){
      debug('変数resultの中身'.print_r($result,true));
      return $result;
    }else{
      debug('クエリ失敗：'.$stmt->errorInfo(),true);
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
  }
}
// レビュー編集
function showReview($c_id, $u_id){
  try {
    $dbh = dbConnect();
    $sql = 'SELECT review FROM review WHERE cinema_id = :c_id AND user_id = :u_id';
    $data = array(':c_id' => $c_id, ':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result){
      debug('クエリ成功：'.print_r($result,true));
      return $result;
    }else{
      debug('クエリ失敗：'.print_r($stmt->errorInfo(),true));
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
    global $error_msg;
    $error_msg['common'] = MSG06;
  }
  
}
// 完了のためのセッション
function successMsg($key){
  if(isset($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

// マイページ関数
function getTimeline($u_id){
  debug('マイページ情報を取得');

  try {
    $dbh = dbConnect();
    $sql = 'SELECT c.id,c.name,c.poster,r.user_id,r.cinema_id,r.review,r.create_date,u.id FROM cinema AS c JOIN review AS r ON c.id = r.cinema_id JOIN users AS u ON u.id = r.user_id WHERE u.id = :u_id';
    $data = array(':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->fetchAll();

    if(!empty($result)){
      return $result;
    }else{
      debug('クエリ失敗');
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
    global $error_msg;
    $error_msg['common'] = MSG06;
  }
}
// お気に入り登録
function isLike($u_id, $c_id){
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM favourite WHERE cinema_id = :c_id AND user_id = :u_id';
    $data = array(':c_id' => $c_id, ':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->rowCount();

    if(!empty($result)){
      return true;
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }
}

// お気に入り表示
function getFavourite($u_id){
  debug('お気に入り表示します');
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM favourite AS f JOIN cinema AS c ON f.cinema_id = c.id WHERE f.user_id = :u_id';
    $data = array(':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->fetchAll();

    return $result;
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }
}