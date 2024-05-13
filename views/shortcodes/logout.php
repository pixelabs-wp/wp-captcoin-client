<?php
class bcLogout
{
  public static function render($atts)
  {

    $user_ID = get_current_user_id();
    $user = wp_get_current_user();

    $atts = array(
      'text' => NULL,
      'class' => NULL
    );

        
     // return "Should Show Login Form";
     $loginUrl = wp_logout_url( $_SERVER['REQUEST_URI'] );
      return str_replace('&amp;', '&', $loginUrl);
      
    
  }


  public static function logout_API()
  {
  }
  
  public static function wp_logout_and_redirect() {
        // Log out the user
        wp_logout();
    
        // Get the current page URL
        $redirect_url = $_SERVER['REQUEST_URI'];
    
        // Redirect the user to the same page after logout
        wp_redirect($redirect_url);
    
        exit;
    }
    
    public static function wp_logout_and_redirect_main() {
        // Log out the user
        wp_logout();
        // Get the current page URL
    $baseurl_for_topics_page = get_option('baseurl_for_topics_page');
    
        // Redirect the user to the same page after logout
        wp_redirect($baseurl_for_topics_page);
    
        exit;
    }
    
    
}
