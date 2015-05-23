<?php
/*
Template Name: welcome
*/
?>
<?php
function parse_signed_request($signed_request, $secret) {
      list($encoded_sig, $payload) = explode('.', $signed_request, 2);
      // decode the data
      $sig = base64_url_decode($encoded_sig);
      $data = json_decode(base64_url_decode($payload), true);
      if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
          error_log('Unknown algorithm. Expected HMAC-SHA256');
          return null;
      }
      // check sig
      $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
      if ($sig !== $expected_sig) {
          error_log('Bad Signed JSON signature!');
          return null;
      }
      return $data; 
}
function base64_url_decode($input) {
      return base64_decode(strtr($input, '-_', '+/'));
}
 
     if (isset($_POST['signed_request']) ) {
         $fb_data = parse_signed_request($_POST['signed_request'], 'ここにアプリのシークレットキー');
     if ($fb_data) {
            //いいね！を押しているか判別
         if( $fb_data['page']['liked'] ){
             //押した人が見れるafter.phpへ
             include 'after.php';
         } else {
             //押していない人はbefore.php
             include 'before.php';
         }
     } else {
         echo 'シークレットキーが入力されていないか、間違いがあります';
     }
} else {
        echo 'facebookから表示してください';
     }
 
?>