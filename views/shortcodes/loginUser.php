<?php
class bcLoginUser
{
  public static function render($atts)
  {

    $user_ID = get_current_user_id();
    $user = wp_get_current_user();

    $atts = array(
      'text' => NULL,
      'class' => NULL
    );

$urlParams="";
    if ($user->{'ID'} !== 0) {
      if (get_user_meta($user_ID, 'userData', true)) {
        return "";
      } else {
		 //return "should show reg form";
        return bcRegisterUser::render("");
      }
    } else {
        if(isset($_GET["bcsingleid"])){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $urlParams = "&state=url=".$actual_link;
        }
        
     // return "Should Show Login Form";
      return Cognito_Login_Generate_Strings::a_tag($atts,$urlParams);
    }
  }


  public static function loginUser_API()
  {
  }
}
