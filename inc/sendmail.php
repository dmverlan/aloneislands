<?
function send_mail($admin, $body, $email)
{
  $subject = '=?utf-8?B?'.base64_encode('Заполнена форма на сайте').'?=';
  $boundary = "--".md5(uniqid(time())); // генерируем разделитель
  $headers = "From: ".strtoupper($_SERVER['SERVER_NAME'])." <".$email.">\r\n";   
  $headers .= "Return-path: <".$email.">\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=utf-8; boundary=\"".$boundary."\"\r\n";
 
  $multipart = $body;
 
  if( mail($admin, $subject, $multipart, $headers) )
    return true;
  else
    return false;
}
?>
