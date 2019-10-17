$(function(){

  $('.searchToggle dt').on('click', function(){
    $(this).next().slideToggle(400);
  });


  // モーダルウィンドウ
  $('.markBtn').on('click', function(){
    // 背景白に
    $('.overWrap').fadeIn(400, function(){
      // レビュー欄表示
      var $review = $('.reviewBox');

      // 横幅計算
      var $winWidth = $(window).width(),
          boxWidth = $review.width(),
          calcW = ($winWidth - boxWidth) / 2,
          w = $review.css({'left': + calcW + 'px'});

      // 高さ計算
      var $winHeight = $(window).height(),
          boxHeight = $review.height(),
          calcH = ($winHeight - boxHeight) / 2,
          h = $review.css({'top': + calcH + 'px'});

      $review.show().resize(w, h);
      

      // 消すボタン
      $('.overWrap, .close').on('click', function(){
        $('.overWrap, .reviewBox').fadeOut(400);
        $review.hide();
      })
    });
  });

  // 消す前のアラート
  $('.filmDelBtn').on('click', function(){
    var conf = window.confirm('この映画情報を削除しますか？');
    if(conf){
      return true;
    }else{
      return false;
    }
  });
  $('.admin_delete').on('click', function () {
    var adConf = window.confirm('この管理者を削除しますか？');
    if (adConf) {
      return true;
    } else {
      return false;
    }
  });

  // 画像ライブプレビュー
  $('.input-files').on('change', function(e){
    var file = this.files[0],
        $img = $(this).parents('.filmImg').find('.live-prev'),
        fileReader = new FileReader();

    fileReader.onload = function(event){
      $img.attr('src', event.target.result).show();
    };

    fileReader.readAsDataURL(file);

  });

  // フッター調整
  var $ftr = $('footer');
  if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight()){
    $ftr.attr({'style': 'position: fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
    $ftr.css('margin-top', '0');
  }

  var $msgArea = $('.msg-suc');
  if($msgArea.text().length){
    $msgArea.fadeIn(400);
    setTimeout(function(){$msgArea.fadeOut(400)},3000);
  }

  // ajaxお気に入り
  var $fav,
      cine_id;
  $fav = $('.js-click-fav') || null;
  cine_id = $fav.data('cinemaid') || null;
  
  if (cine_id !== undefined && cine_id !== null){
    $fav.on('click', function(){

      var $this = $(this);
      $.ajax({
        type: "POST",
        url: "ajaxFavourite.php",
        data: { cinemaid : cine_id }
      }).done(function ( data ){
        $this.toggleClass('active');
        console.log('登録成功');
      }).fail(function ( msg ){
        console.log('登録失敗');
      });
    });
  }

});